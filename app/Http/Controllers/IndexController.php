<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;
use App\Models\User;

class IndexController extends Controller
{
    public function home(?int $chat_id = null)
    {
        $users = User::select('id', 'name', 'avatar_path')->get();
        $user = Auth::user();
        $chats = $user->getChatsWithUsers();
        $chatIds = $chats->pluck('id');

        $notifications = $user->notifications()->get()->map(function ($notification) {
            $notification->pivot->makeHidden(['created_at', 'updated_at']);
            return $notification;
        });

        $messages = null;

        if ($chat_id) {
            $chat = $chats->find($chat_id);

            if (!$chat) {
                return redirect()->route('home');
            }

            // 1. Завантажити повідомлення
            $messages = $chat->messages()->get();

            // 2. Оновити прочитані нотифікації тільки для цього чату
            DB::table('notification_user')
                ->where('user_id', $user->id)
                ->whereIn('notification_id', function ($query) use ($chat_id) {
                    $query->select('id')
                        ->from('notifications')
                        ->where('chat_id', $chat_id);
                })
                ->update(['is_read' => 1]);
        }

        return Inertia::render('Chat/Chat', [
            'users' => $users,
            'chats' => $chats,
            'messages' => $messages,
            'chat_id' => $chat_id,
            'notifications' => $notifications,
        ]);
    }


}
