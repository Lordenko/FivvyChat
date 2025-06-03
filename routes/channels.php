<?php

use App\Broadcasting\MessageDeleteChannel;
use App\Broadcasting\MessageEditChannel;
use App\Broadcasting\MessageSentChannel;
use App\Broadcasting\ChatCreateChannel;
use App\Broadcasting\ProfileChangeChannel;
use App\Broadcasting\NotificationSentChannel;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

// Global

// To one user
Broadcast::channel('NewChat.{user_id}', ChatCreateChannel::class);
Broadcast::channel('Notification.{user_id}', NotificationSentChannel::class);
Broadcast::channel('ProfileChangeChannel.{user_id}', ProfileChangeChannel::class);

// In-Chat
Broadcast::channel('Chat.{chat_id}', MessageSentChannel::class);
Broadcast::channel('MessageEditChannel.{chat_id}', MessageEditChannel::class);
Broadcast::channel('MessageDeleteChannel.{chat_id}', MessageDeleteChannel::class);
