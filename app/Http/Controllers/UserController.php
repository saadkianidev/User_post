<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of all users (DataTables JSON or view).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('id', '!=', Auth::id())
                ->select(['id', 'name', 'email', 'created_at']);

            return datatables()->of($users)
                ->addColumn('avatar', function ($user) {
                    $initials = collect(explode(' ', $user->name))
                        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                        ->join('');
                    
                    return '<div class="avatar-circle bg-primary text-white">' . $initials . '</div>';
                })
                ->addColumn('action', function ($user) {
                    return '
                        <button class="btn btn-sm btn-primary message-btn" 
                                data-id="' . $user->id . '" 
                                data-name="' . e($user->name) . '">
                            <i class="bi bi-chat-dots-fill"></i> Message
                        </button>
                    ';
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->format('M d, Y');
                })
                ->rawColumns(['avatar', 'action'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Send a message to a specific user.
     */
    public function sendMessage(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'content'     => $request->message,
            'read_at'     => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent to ' . $user->name,
            'data'    => $message
        ]);
    }
}