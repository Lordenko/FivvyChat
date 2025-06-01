<?php

namespace App\Broadcasting;

use App\Models\User;

class NotificationSentChannel
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
    public function join(User $user, int $user_id): bool
    {
        return $user->id === $user_id;
    }
}
