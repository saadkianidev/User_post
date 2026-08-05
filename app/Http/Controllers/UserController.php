<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
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

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'content'     => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent to ' . $user->name,
        ]);
    }
}