<?php

namespace App\Http\Controllers;

use App\Events\Chat\MessageSent;
use App\Events\ChatCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\User;
use App\Http\Requests\StoreChatRequest;
use Inertia\Inertia;

class ChatController extends Controller
{

    public static function index_byUserId(string $user_id)
    {
        $user = User::find($user_id);

        if ($user) {
            return $user->chats()->get();
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Chat::with('users', 'messages')->get();
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
     * Request : user_ids[]
     *
     */
    public function store(StoreChatRequest $request)
    {
        $data = $request->validated();
        $user_ids = collect($request->user_ids)->sort()->values();

        $existingChat = Chat::where('type', 'direct')
            ->whereHas('users', function ($q) use ($user_ids) {
                $q->whereIn('users.id', $user_ids);
            }, '=', $user_ids->count())
            ->withCount('users')
            ->get()
            ->firstWhere('users_count', $user_ids->count());

        if ($existingChat) {
            return redirect()->route('chat.show', $existingChat);
        }

        $chat = Chat::create([
            'type' => $data['type'],
        ]);
        $chat->users()->attach($user_ids);

        foreach ($chat->users as $user) {
            event(new ChatCreate($chat, $user));
        }

        return redirect()->route('chat.show', $chat->id);
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
        $chat = Chat::find($id);
        $chat->users()->sync($request->user_ids);
        return redirect()->route('chats.show', $chat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Chat::find($id)->delete();
        return redirect()->intended(route('home'));
    }
}
