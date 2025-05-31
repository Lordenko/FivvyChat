<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($chatId)
    {
        return Message::where('chat_id', $chatId)->with('user')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * Requist : message_text:String, user_id:String, chat_id:String
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'message_text' => 'required|string',
            'chat_id' => 'required|exists:chats,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $message = Message::create($request->only('message_text', 'chat_id', 'user_id'));



        $notification = Notification::create([
            'chat_id' => $message->chat_id,
            'message' => $message->message_text,
        ]);

        $chatUsers = $message->chat->users;

        foreach ($chatUsers as $user) {
            if ($user->id !== $message->user_id) {
                $notification->recipients()->attach($user->id);
            }
        }

        $chat = Chat::find($message->chat_id);
        $chat->last_message = $message->message_text;
        $chat->save();

        $notification->load('recipients');

        event(new MessageSent(
            $notification,
            $message,
            $message->chat_id,
        ));


        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
