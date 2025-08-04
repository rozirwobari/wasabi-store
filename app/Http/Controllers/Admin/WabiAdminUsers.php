<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WabiAdminUsers
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.content.pengguna', compact('users'));
    }

    public function editpengguna($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return redirect()->back()->with('alert', [
                'title' => 'Kesalahan',
                'message' => 'User Id Tidak Ditemukan',
                'type' => 'warning',
            ]);
        };
        return view('dashboard.content.editpengguna', compact('user'));
    }

    public function updatepengguna(Request $request)
    {
        $user_id = $request->user_id;
        $name = $request->name;
        $email = $request->email;
        $role = $request->role;
        $is_active = $request->is_active;
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return redirect()->back()->with('alert', [
                'title' => 'Kesalahan',
                'text' => 'User Id Tidak Ditemukan',
                'type' => 'warning',
            ]);
        };

        $user->update([
            'name' => $name,
            'email' => $email,
            'role' => $request->role,
            'is_active' => $is_active,
        ]);

        return redirect()->route('admin.pengguna')->with('alert', [
            'title' => 'Berhasil',
            'text' => "User Berhasil Diupdate ".$name,
            'type' => "success"
        ]);
    }

    public function savepengguna(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $role = $request->role;
        $is_active = $request->is_active;

        // Cek apakah email sudah ada
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            return redirect()->back()->with('alert', [
                'title' => 'Kesalahan',
                'text' => 'Email Sudah Terdaftar',
                'type' => 'warning',
            ]);
        }

        // Buat user baru (bukan update user yang sudah ada)
        $GeneratePassword = Str::random(15);
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'is_active' => $is_active,
            'password' => Hash::make($GeneratePassword), // Atau generate random password
        ]);

        // Kirim email aktivasi jika user tidak aktif
        if ($is_active == 0) {
            $activationToken = Str::random(60);

            // Simpan token ke database (misal di tabel user_activations atau kolom activation_token)
            $user->update(['activation_token' => $activationToken]);

            $this->sendActivationEmail($user, $activationToken, $GeneratePassword);
        }

        return redirect()->route('admin.pengguna')->with('alert', [
            'title' => 'Berhasil',
            'text' => "User Berhasil Ditambah " . $name,
            'type' => "success"
        ]);
    }

    private function sendActivationEmail($user, $token, $password)
    {
        $activationUrl = route('account.activate', $token);
        $appName = config('app.name');
        $subject = "Aktivasi Akun - {$appName}";
        $data = [
            'user' => $user,
            'activationUrl' => $activationUrl,
            'appName' => $appName,
            'password' => $password
        ];

        Mail::send('auth.template.verify-mail-password', $data, function ($mail) use ($user, $subject) {
            $mail->to($user->email, $user->name)
                 ->subject($subject);
        });
    }

    public function tambahpengguna()
    {
        return view('dashboard.content.addpengguna');
    }

    public function hapususer(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::where('id', $user_id)->first();
        if ($user) {
            $user->delete();
            return response()->json([
                'title' => 'Berhasil',
                'text' => 'User berhasil Dihapus.',
                'type' => 'success',
            ], 200);
        }
        return response()->json([
            'title' => 'Gagal',
            'text' => 'User Tidak Ditemukan.',
            'type' => 'warning',
        ], 500);
    }

    public function setting()
    {
        $user = User::where('id', auth()->id())->first();
        return view('dashboard.content.setting', compact('user'));
    }
}
