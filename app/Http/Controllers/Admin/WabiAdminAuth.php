<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WabiAdminAuth
{
    /**
     * Display a listing of the resource.
     */
    public function admin()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['admin', 'superadmin'])) {
                return redirect()->route('admin.home');
            } else {
                return redirect('/');
            }
        }
        return view('dashboard.auth.content.login');
    }

    /**
     * Display a listing of the resource.
     */
    public function authadmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if (in_array($user->role, ['admin', 'superadmin'])) {
                    Auth::login($user, $request->has('remember'));
                    return redirect('/admin');
                } else {
                    return redirect('/');
                }
            } else {
                return back()->withErrors(['password' => 'Password Salah.'])->withInput();
            }
        }
        return back()->withErrors(['email' => 'Email Tidak Ditemukan.'])->withInput();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        return view('dashboard.content.index');
    }
}
