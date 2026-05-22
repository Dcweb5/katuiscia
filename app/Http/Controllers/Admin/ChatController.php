<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = ChatMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('session_id');

        $unread = ChatMessage::where('is_read', false)->count();

        return view('admin.chat.index', compact('conversations', 'unread'));
    }

    public function markRead(ChatMessage $message)
    {
        $message->update(['is_read' => true]);
        return back();
    }
}
