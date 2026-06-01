<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use App\Notifications\NewsletterNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $subscribersQuery = NewsletterSubscriber::orderBy('created_at', 'desc');
        if ($search) {
            $subscribersQuery->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $subscribers = $subscribersQuery->paginate(20, ['*'], 'subscribers_page');
        $campaigns = Newsletter::orderBy('created_at', 'desc')->paginate(10, ['*'], 'campaigns_page');
        
        $activeCount = NewsletterSubscriber::where('is_active', true)->count();
        $inactiveCount = NewsletterSubscriber::where('is_active', false)->count();

        return view('admin.newsletter.index', compact('subscribers', 'campaigns', 'activeCount', 'inactiveCount', 'search'));
    }

    public function storeSubscriber(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'nullable|string|max:255',
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            ['name' => $request->name, 'is_active' => true]
        );

        return back()->with('success', "L'abonné {$request->email} a été ajouté avec succès.");
    }

    public function toggleSubscriber(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);
        $status = $subscriber->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "L'abonnement de {$subscriber->email} a été {$status}.");
    }

    public function destroySubscriber(NewsletterSubscriber $subscriber)
    {
        $email = $subscriber->email;
        $subscriber->delete();
        return back()->with('success', "L'abonné {$email} a été supprimé.");
    }

    public function createCampaign()
    {
        $activeCount = NewsletterSubscriber::where('is_active', true)->count();
        return view('admin.newsletter.create', compact('activeCount'));
    }

    public function sendCampaign(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        if ($subscribers->isEmpty()) {
            return redirect()->route('admin.newsletter.index')->with('error', "Aucun abonné actif pour recevoir cette campagne.");
        }

        // Save campaign record
        $campaign = Newsletter::create([
            'subject' => $request->subject,
            'content' => $request->content,
            'sent_at' => now(),
        ]);

        $successCount = 0;
        $failCount = 0;

        foreach ($subscribers as $sub) {
            try {
                Notification::route('mail', $sub->email)->notify(
                    new NewsletterNotification($request->subject, $request->content, $sub->email)
                );
                $successCount++;
            } catch (\Exception $e) {
                Log::error("Failed to send newsletter to {$sub->email}: " . $e->getMessage());
                $failCount++;
            }
        }

        $msg = "Campagne envoyée avec succès à {$successCount} abonnés.";
        if ($failCount > 0) {
            $msg .= " ({$failCount} échecs d'envoi).";
        }

        return redirect()->route('admin.newsletter.index')->with('success', $msg);
    }
}
