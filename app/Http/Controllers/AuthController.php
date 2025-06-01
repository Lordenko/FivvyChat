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
        ]);

        if (Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors([
            'name' => 'Data is not match!',
            'password' => 'Data is not match!'
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
