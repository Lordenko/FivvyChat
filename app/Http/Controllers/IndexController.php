<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;
use App\Models\User;

class IndexController extends Controller
{
    public function home(?int $chat_id = null)
    {
        $users = User::all();

        $user = Auth::user();
        $chats = $user->getChatsWithUsers();
        $messages = null;

        if ($chat_id) {
            $messages = $chats->find($chat_id)->messages()->get();
        }

        return Inertia::render('Chat/Chat', [
            'users' => $users,
            'chats' => $chats,
            'messages' => $messages,
            'chat_id' => $chat_id,
        ]);
    }

}
