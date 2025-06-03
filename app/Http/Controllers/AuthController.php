<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        sleep(1);

        $fields = $request->validate([
            'name' => ['required', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:5'],
            'agree' => ['accepted']
        ], [
            'name.required' => 'Ім’я обовʼязкове.',
            'name.unique' => 'Таке ім’я вже існує.',
            'email.required' => 'E-mail обовʼязковий.',
            'email.email' => 'Введіть коректний e-mail.',
            'email.unique' => 'Цей e-mail вже зареєстровано.',
            'password.required' => 'Пароль обовʼязковий.',
            'password.confirmed' => 'Паролі не збігаються.',
            'password.min' => 'Пароль має містити щонайменше 5 символів.',
            'agree.accepted' => 'Ви повинні прийняти умови.',
        ]);


        $user = User::create($fields);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function login(Request $request)
    {
        sleep(1);

        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'password' => ['required'],
        ], [
            'name.required' => 'Ім’я обовʼязкове.',
            'password.required' => 'Пароль обовʼязковий.',
        ]);


        if (Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors([
            'name' => 'Дані не збігаються!',
            'password' => 'Дані не збігаються!'
        ])->onlyInput('name');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
