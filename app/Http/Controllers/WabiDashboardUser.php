<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CartModel;
use App\Models\OrdersModel;

class WabiDashboardUser extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        $orders = OrdersModel::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view("store.content.dashboard.index", compact("carts", "orders"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function profile()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        return view("store.content.dashboard.profile", compact("carts"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function orders()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        $orders = OrdersModel::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view("store.content.dashboard.orders", compact("carts", "orders"));
    }

    /**
     * Display the specified resource.
     */
    public function profileupdate(Request $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');
        $steam_hex = $request->input('steam_hex');
        User::where('email', $email)->update([
            'name' => $name,
            'steam_hex' => $steam_hex,
        ]);
        return redirect()->back()->with('alert', [
            'title' => 'Berhasil',
            'message' => 'Profile berhasil diperbarui',
            'type' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function changepassword(Request $request)
    {
        $password = $request->input('password');
        $new_password = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');
        
        try {
            $validatedData = $request->validate([
                'password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ], [
                'password.required' => 'Password saat ini harus diisi.',
                'new_password.required' => 'Password baru harus diisi.',
                'new_password.min' => 'Password baru minimal 6 karakter.',
                'confirm_password.required' => 'Konfirmasi password harus diisi.',
                'confirm_password.same' => 'Konfirmasi password tidak sesuai dengan password baru.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator);
        }

        // Cek apakah password lama sesuai
        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['password' => 'Password lama tidak sesuai.']);
        }
        
        // Update password ke database
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        // Logout user setelah berhasil update password
        Auth::logout();
        
        // Invalidate session dan regenerate token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('alert', [
            'title' => 'Berhasil',
            'message' => 'Password berhasil diubah. Silakan login kembali.',
            'type' => 'success',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function settings()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        return view("store.content.dashboard.settings", compact("carts"));
    }
}
