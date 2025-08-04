<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;

use App\Models\User;
use App\Mail\AccountActivationMail;

class RegisterController
{
    public function index()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password'
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama lengkap maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password',
        ]);

        $activationToken = Str::random(60);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activation_token' => $activationToken,
        ]);
        $this->sendActivationEmail($user, $activationToken);


        return redirect('/login')->with('alert', [
            'title' => 'Berhasil',
            'message' => $request->email.' Berhasil Terdaftar.Periksa Email Kamu Untuk Verifikasi Email',
            'type' => 'success',
        ]);
    }

     public function activate(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect('/login')->with('alert', [
                'title' => 'Error',
                'message' => 'Token aktivasi tidak valid atau sudah kedaluwarsa.',
                'type' => 'error',
            ]);
        }

        if ($user->is_active) {
            return redirect('/login')->with('alert', [
                'title' => 'Info',
                'message' => 'Akun Anda sudah aktif sebelumnya.',
                'type' => 'info',
            ]);
        }
        $user->update([
            'is_active' => true,
            'activation_token' => null,
            'email_verified_at' => now(),
        ]);

        return redirect('/login')->with('alert', [
            'title' => 'Berhasil',
            'message' => 'Akun Anda berhasil diaktivasi! Silakan login.',
            'type' => 'success',
        ]);
    }

    public function resendActivation(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user->is_active) {
            return back()->with('alert', [
                'title' => 'Info',
                'message' => 'Akun Anda sudah aktif.',
                'type' => 'info',
            ]);
        }
        if (!$user->activation_token) {
            $user->update([
                'activation_token' => Str::random(60)
            ]);
        }
        $this->sendActivationEmail($user, $user->activation_token);

        return back()->with('alert', [
            'title' => 'Email Terkirim',
            'message' => 'Link aktivasi telah dikirim ulang ke email Anda.',
            'type' => 'success',
        ]);
    }

    private function sendActivationEmail($user, $token)
    {
        $activationUrl = route('account.activate', $token);
        $appName = config('app.name');
        $subject = "Aktivasi Akun - {$appName}";
        $data = [
            'user' => $user,
            'activationUrl' => $activationUrl,
            'appName' => $appName,
        ];

        Mail::send('auth.template.verify-mail', $data, function ($mail) use ($user, $subject) {
            $mail->to($user->email, $user->name)
                 ->subject($subject);
        });
    }
}
