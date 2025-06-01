<?php

use App\Broadcasting\MessageSentChannel;
use App\Broadcasting\ChatCreateChannel;
use App\Broadcasting\ProfileChangeChannel;
use App\Broadcasting\NotificationSentChannel;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('Chat.{chat_id}', MessageSentChannel::class);
Broadcast::channel('NewChat.{user_id}', ChatCreateChannel::class);
Broadcast::channel('Notification.{user_id}', NotificationSentChannel::class);
Broadcast::channel('ProfileChangeChannel.{user_id}', ProfileChangeChannel::class);
