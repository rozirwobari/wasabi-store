<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class LoginController
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('email', 'password');
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->has('remember');

        $user = User::where('email', $email)->first();
    
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar.',
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'password' => 'Password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::flush();
        return redirect('/login')->with('success', 'Anda berhasil logout');
    }
}