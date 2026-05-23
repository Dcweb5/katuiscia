<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            if ($secret) {
                $event = Webhook::constructEvent($payload, $sigHeader, $secret);
            } else {
                $event = json_decode($payload);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $order = Order::where('stripe_session_id', $session->id)->first();

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'paid_at' => now(),
                ]);

                // Points fidélité
                if ($order->user_id) {
                    $order->user->increment('loyalty_points', (int) $order->total);
                }

                // Vider le panier
                if ($order->user_id) {
                    $cart = \App\Models\Cart::where('user_id', $order->user_id)->first();
                } else {
                    $cart = \App\Models\Cart::where('email', $order->email)->first();
                }
                if ($cart) {
                    $cart->items()->delete();
                    $cart->delete();
                }

                // Générer et envoyer la facture
                try {
                    $this->generateAndSendInvoice($order);
                } catch (\Exception $e) {
                    \Log::error('Invoice generation failed: ' . $e->getMessage());
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function generateAndSendInvoice(Order $order): void
    {
        $count = \App\Models\Invoice::count();
        $invoiceNumber = 'FACT-' . now()->format('Y') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $invoice = \App\Models\Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'file_path' => 'invoices/' . $invoiceNumber . '.pdf',
            'total' => $order->total,
            'is_emailed' => false,
        ]);

        $order->load('items');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.template', compact('order', 'invoice'));
        $pdfPath = storage_path('app/invoices/' . $invoiceNumber . '.pdf');

        if (!is_dir(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }

        $pdf->save($pdfPath);

        // Envoyer l'email avec la facture en pièce jointe
        \Mail::to($order->email, $order->firstname . ' ' . $order->lastname)
            ->send(new \App\Mail\InvoiceMail($order, $invoice, $pdfPath));

        $invoice->update(['is_emailed' => true]);
    }
}
