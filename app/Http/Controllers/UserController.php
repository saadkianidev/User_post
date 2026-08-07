<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Notifications\MessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('users.index', compact('users'));
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'content'     => $request->message,
            'parent_id'   => $request->parent_id ?? null,
        ]);

        $message->load('sender');

        $user->notify(new MessageNotification($message));

        return response()->json([
            'success' => true,
            'message' => 'Message sent to ' . $user->name,
        ]);
    }

    public function markAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}