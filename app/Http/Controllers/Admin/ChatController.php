<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatMessage::with('user')->orderBy('created_at', 'desc');

        // Search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('reply', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('firstname', 'like', "%{$search}%")->orWhere('lastname', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        // Filter by read status
        if ($request->query('filter') === 'unread') {
            $query->where('is_read', false);
        }

        $allMessages = $query->get();

        // Group by session_id for conversation list
        $conversations = $allMessages->groupBy('session_id')->map(function ($msgs) {
            return [
                'session_id' => $msgs->first()->session_id,
                'name' => $msgs->first()->user?->full_name ?? 'Visiteur anonyme',
                'email' => $msgs->first()->user?->email ?? null,
                'last_message' => $msgs->first()->message,
                'last_reply' => $msgs->first()->reply,
                'last_date' => $msgs->first()->created_at,
                'total' => $msgs->count(),
                'unread' => $msgs->where('is_read', false)->count(),
                'messages' => $msgs->sortBy('created_at'),
            ];
        })->sortByDesc('last_date')->values();

        // Selected conversation
        $activeSession = $request->query('session');
        $activeConversation = null;
        if ($activeSession) {
            $activeConversation = $conversations->firstWhere('session_id', $activeSession);
            // Mark all as read
            if ($activeConversation) {
                ChatMessage::where('session_id', $activeSession)->where('is_read', false)->update(['is_read' => true]);
                $activeConversation['unread'] = 0;
            }
        }

        $unread = ChatMessage::where('is_read', false)->count();

        return view('admin.chat.index', compact('conversations', 'unread', 'activeSession', 'activeConversation'));
    }

    public function markRead(ChatMessage $message)
    {
        $message->update(['is_read' => true]);
        return back();
    }
}
