<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\CartModel;
use App\Models\OrdersModel;
use App\Models\WabiGameProfile;
use App\Http\Controllers\WabiApiController;

class WabiDashboardUser
{
    protected $gameEndpoint;

    public function __construct()
    {
        $this->gameEndpoint = "http://208.76.40.92/";
    }

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
        User::where('email', $email)->update([
            'name' => $name,
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

        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['password' => 'Password lama tidak sesuai.']);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
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

    /**
     * Update the specified resource in storage.
     */
    public function dataplayers()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        $steamhexs = WabiGameProfile::where('user_id', auth()->id())->get();
        return view("store.content.dashboard.dataplayer", compact("carts", "steamhexs"));
    }

    public function GetPlayerData(Request $request)
    {
        $WabiApi = new WabiApiController();
        try {
            $data = [
                'identifier' => $request->identifier // atau nilai langsung
            ];
            $response = $WabiApi->GetPlayerData($data);
            if ($response) {
                $dataRespon = $response;
                return response()->json([
                    'success' => true,
                    'data' => $dataRespon
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "(".$request->identifier.") ".$dataRespon['message'],
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function SavePlayerData(Request $request)
    {

        try {
            $WabiApi = new WabiApiController();
            $dataPlayeres = WabiGameProfile::where('user_id', auth()->id())->where('identifier', $request->identifier)->get();
            if (count($dataPlayeres) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Data Sudah Terdaftar",
                ]);
            }
            $profile = WabiGameProfile::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'identifier' => $request->identifier,
            ]);
            if ($profile) {
                $WabiApi->LinkedAccount([
                    'identifier' => $request->identifier,
                    'email' => auth()->user()->email,
                    'user_id' => auth()->id(),
                ]);
                return response()->json([
                    'success' => true,
                    'data' => $profile,
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }

    }

    public function updateplayerdata(Request $request)
    {
        $WabiApi = new WabiApiController();
        $dataPlayer = WabiGameProfile::where('user_id', auth()->id())->where('identifier', $request->identifier)->first();
        if (!$dataPlayer) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan 2',
            ], 500);
        }
        try {
            $data = [
                'identifier' => $request->identifier
            ];
            $response = $WabiApi->GetPlayerData($data);
            if ($response) {
                $playerData = $response;
                $dataPlayer->name = $playerData['data']['name'];
                $dataPlayer->save();

                return response()->json([
                    'success' => true,
                    'message' => "Data Berhasil Diupdate",
                    'data' => $playerData['data']
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch player data',
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteplayerdata(Request $request)
    {
        $dataPlayer = WabiGameProfile::where('user_id', auth()->id())->where('identifier', $request->identifier)->first();
        if (!$dataPlayer) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 500);
        }
        $dataPlayer->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhaisl Dihapus',
        ], 200);
    }

    public function resendlinked(Request $request)
    {
        $dataPlayer = WabiGameProfile::where('user_id', auth()->id())->where('identifier', $request->identifier)->first();
        if (!$dataPlayer) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 500);
        }
        $WabiApi->LinkedAccount([
            'identifier' => $request->identifier,
            'email' => auth()->user()->email,
            'user_id' => auth()->id(),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Dikirimkan',
        ], 500);
    }
}
