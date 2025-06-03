<?php

namespace App\Broadcasting;

use App\Models\User;

class MessageDeleteChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, $chat_id): array|bool
    {
        return $user->hasAccessToChat($chat_id);
    }
}
