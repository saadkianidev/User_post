<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message_id'  => $this->message->id,
            'sender_id'   => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'content'     => \Str::limit($this->message->content, 60),
            'parent_id'   => $this->message->parent_id,
            'is_reply'    => $this->message->parent_id !== null,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'   => $this->id,
            'data' => $this->toDatabase($notifiable),
            'created_at' => now()->toIso8601String(),
        ]);
    }

    public function broadcastType(): string
    {
        return 'message.received';
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('App.Models.User.' . $this->message->receiver_id)];
    }
}