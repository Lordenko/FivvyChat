<?php

use App\Broadcasting\MessageSentChannel;
use App\Broadcasting\ChatCreateChannel;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('Chat.{chat_id}', MessageSentChannel::class);

Broadcast::channel('NewChat.{user_id}', ChatCreateChannel::class);
