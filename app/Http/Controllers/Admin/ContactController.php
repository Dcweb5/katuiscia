<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index() {
        $messages = ContactMessage::orderBy('created_at','desc')->paginate(20);
        $unread = ContactMessage::where('is_read',false)->count();
        return view('admin.contacts.index', compact('messages','unread'));
    }
    public function markRead(ContactMessage $message) {
        $message->update(['is_read' => !$message->is_read]);
        return back();
    }
    public function destroy(ContactMessage $message) {
        $message->delete();
        return back()->with('success', 'Message supprimé.');
    }
    public function bulkDestroy(Request $request) {
        $ids = explode(',', $request->input('ids', ''));
        ContactMessage::whereIn('id', $ids)->delete();
        return back()->with('success', count($ids).' message(s) supprimé(s).');
    }
    public function reply(ContactMessage $message, Request $request) {
        $request->validate(['reply_body' => 'required|string|min:1']);
        Mail::raw($request->reply_body, function ($mail) use ($message) {
            $mail->to($message->email, $message->name)
                 ->from('contact@katuiscia.com', 'KATUISCIA')
                 ->replyTo('contact@katuiscia.com', 'KATUISCIA')
                 ->subject('Re: '.($message->subject ?? 'Votre message'));
        });
        return back()->with('success', 'Réponse envoyée à '.$message->email.'.');
    }
}
