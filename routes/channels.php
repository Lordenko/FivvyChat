<?php

use App\Broadcasting\MessageSentChannel;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('Chat.{chat_id}', MessageSentChannel::class);
