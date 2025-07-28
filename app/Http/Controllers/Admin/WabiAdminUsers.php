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
                'message' => 'User Id Tidak Ditemukan',
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
