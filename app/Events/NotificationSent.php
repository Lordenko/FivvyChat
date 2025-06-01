<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;
    public Message $message;
    public String $user_id;
    public String $chat_id;

    public function __construct(Notification $notification, Message $message, String $user_id, String $chat_id)
    {
        $this->notification = $notification;
        $this->message = $message;
        $this->user_id = $user_id;
        $this->chat_id = $chat_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('Notification.' . $this->user_id),
        ];
    }
}
