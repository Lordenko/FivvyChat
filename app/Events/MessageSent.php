<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;



class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;
    public Message $message;
    public String $chat_id;

    public function __construct(Notification $notification, Message $message, String $chat_id)
    {
        $this->notification = $notification;
        $this->message = $message;
        $this->chat_id = $chat_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('Chat.' . $this->chat_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => $this->notification->toArray(),
            'message' => $this->message->toArray(),
        ];
    }

}
