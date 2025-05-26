<?php

namespace App\Http\Controllers;

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

        Message::create($request->only('message_text', 'chat_id', 'user_id'));

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
