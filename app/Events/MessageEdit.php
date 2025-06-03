<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEdit implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public string $chat_id;
    public string $user_id;

    public function __construct(Message $message, string $chat_id, string $user_id)
    {
        $this->message = $message;
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('MessageEditChannel.'.$this->chat_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
        ];
    }
}
