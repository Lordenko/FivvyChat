<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;
use App\Models\User;

class IndexController extends Controller
{
    public function home(?int $chat_id = null)
    {
        $users = User::select('id', 'name')->get();

        $user = Auth::user();
        $chats = $user->getChatsWithUsers();

        $chatIds = $chats->pluck('id');

        $notifications = $user->notifications()->get()->map(function ($notification) {
            $notification->pivot->makeHidden(['created_at', 'updated_at']); // опційно
            return $notification;
        });


//        $notificationForDelete = Notification::whereIn('chat_id', $chatIds)
//            ->where('user_id', '=', $user->id)
//            ->get();
//        dd($notificationForDelete);

        $messages = null;

        if ($chat_id) {
            $chat = $chats->find($chat_id);
            if ($chat) {
                $messages = $chat->messages()->get();
            }
            else {
                return redirect()->route('home');
            }
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
