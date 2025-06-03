<?php

namespace App\Http\Controllers;

use App\Events\ProfileChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048']
        ], [
            'avatar.required' => 'Будь ласка, виберіть файл для аватарки.',
            'avatar.image' => 'Файл має бути зображенням (JPEG, PNG, тощо).',
            'avatar.max' => 'Розмір зображення не повинен перевищувати 2MB.',
        ]);


        $user = $request->user();

        if ($user->avatar_path && $user->avatar_path != 'avatars/default_pfp.png') {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $filename = uniqid() . '_' . $request->file('avatar')->getClientOriginalName();
        $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');

        $user->avatar_path = $path;
        $user->save();

        return back()->with('success', 'Avatar updated!');
    }


    public function updateNickname(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:users']
        ], [
            'name.required' => 'Ім’я є обов’язковим полем.',
            'name.string' => 'Ім’я повинно бути рядком.',
            'name.max' => 'Ім’я не може бути довшим за 50 символів.',
            'name.unique' => 'Таке ім’я вже використовується.',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        foreach ($user->chats as $chat) {
            foreach ($chat->users as $chat_user) {
                if ($chat_user->id != $user->id) {
                    event(new ProfileChange(
                        $chat_user->id,
                    ));
                }
            }
        }

        return back()->with('success', 'Nickname updated!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8'],
            'confirm_password' => ['same:new_password']
        ], [
            'current_password.required' => 'Введіть поточний пароль.',
            'new_password.required' => 'Введіть новий пароль.',
            'new_password.min' => 'Новий пароль має містити щонайменше 8 символів.',
            'confirm_password.same' => 'Підтвердження пароля не збігається з новим паролем.',
        ]);


        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated!');
    }
}
