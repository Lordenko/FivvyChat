<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatDelete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Chat $chat;
    public string $user_id;

    public function __construct(Chat $chat, string $user_id)
    {
        $this->chat = $chat;
        $this->user_id = $user_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ChatDeleteChannel.' . $this->user_id),
        ];
    }
}
