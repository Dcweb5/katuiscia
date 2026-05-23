<?php
namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function downloadByToken(string $token)
    {
        $invoice = Invoice::where('public_token', $token)->firstOrFail();
        $path = storage_path('app/' . $invoice->file_path);

        if (!file_exists($path)) abort(404);

        return response()->download($path, 'Facture-' . $invoice->invoice_number . '.pdf');
    }

    public function download(Invoice $invoice)
    {
        if (auth()->id() !== $invoice->order->user_id && !auth()->user()?->is_admin) {
            abort(403);
        }

        $path = storage_path('app/' . $invoice->file_path);
        if (!file_exists($path)) abort(404);

        return response()->download($path, 'Facture-' . $invoice->invoice_number . '.pdf');
    }
}
