<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class RegisterController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'steam_hex' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password'
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama lengkap maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'steam_hex.required' => 'Steam HEX wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'steam_hex' => $request->steam_hex,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('alert', [
            'title' => 'Berhasil',
            'message' => $request->email.' Berhasil Terdaftar',
            'type' => 'success',
        ]);
    }
}
