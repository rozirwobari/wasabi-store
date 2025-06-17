<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ProdukModel;
use App\Models\CartModel;
use App\Models\KategoriModel;
use App\Models\OrdersModel;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class WabiStoreController extends Controller
{

    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
        // Debug: Cek apakah config terbaca
        if (!Config::$serverKey) {
            throw new \Exception('Midtrans Server Key tidak ditemukan. Periksa file .env dan config/midtrans.php');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriModel::all();
        $produks = ProdukModel::all();
        $carts = [];
        if (auth()->check()) {
            $carts = CartModel::where('user_id', auth()->id())->get();
        }
        return view("store.content.index", compact("kategoris", "produks", "carts"));
    }

    /**
     * Display a listing of the resource.
     */
    public function cart()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        return view("store.content.cart", compact("carts"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function dashboard()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        $orders = OrdersModel::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view("store.content.dashboard.index", compact("carts", "orders"));
    }

    /**
     * Store a newly created resource in storage.
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
     * Store a newly created resource in storage.
     */
    public function settings()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        return view("store.content.dashboard.settings", compact("carts"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function orderdetails($invoice = null)
    {
        $orders = OrdersModel::where([
            'user_id' => auth()->id(),
            'no_invoice' => $invoice,
        ])->first();
        $carts = CartModel::where('user_id', auth()->id())->get();
        return view("store.content.dashboard.orderdetails", compact("carts", "orders"));
    }

    /**
     * Display the specified resource.
     */
    public function produkdetail($id = null)
    {
        if ($id or $id != null) {
            $produk = ProdukModel::find($id);
            $carts = CartModel::where('user_id', auth()->id())->get();
            if (!$produk) {
                return redirect()->back()->with('error', 'Product not found');
            }
            return view("store.content.detail", compact("produk", "carts"));
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function addtocart(Request $request)
    {
        $produkId = $request->produk_id;
        $jumlah = $request->jumlah;
        $produk = ProdukModel::find($request->input('produk_id'));
        if (!$produk) {
            return response()->json([
                'success' => false,
                'data' => $request->input('produk_id'),
                'message' => 'Produk Tidak Ditemukan',
            ], 404);
        }

        $CheckCarts = CartModel::where([
            'user_id' => auth()->id(),
            'produk_id' => $produkId,
        ])->first();
        if ($CheckCarts) {
            CartModel::where([
                'user_id' => auth()->id(),
                'produk_id' => $produkId,
            ])->update([
                'jumlah' => $CheckCarts->jumlah + $jumlah,
            ]);
            $getCarts = CartModel::where('user_id', auth()->id())->get();
            return response()->json([
                'success' => true,
                'message' => 'Success!',
                'data' => $getCarts,
            ], 200);
        } else {
            $data = [
                'user_id' => auth()->id(),
                'produk_id' => $produkId,
                'jumlah' => $jumlah,
            ];
            $cart = CartModel::create($data);
            $getCarts = CartModel::where('user_id', auth()->id())->get();
            return response()->json([
                'success' => true,
                'message' => 'Success!',
                'data' => $getCarts,
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function deletecarts(Request $request)
    {
        $cartId = $request->input('cart_id');
        $cart = CartModel::find($cartId);
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found',
            ], 404);
        }
        $cart->delete();
        $getCarts = CartModel::where('user_id', auth()->id())->get();
        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Dihapus',
            'data' => $getCarts,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function updatecarts(Request $request)
    {
        $cartId = $request->input('cart_id');
        $jumlah = $request->input('jumlah');
        $cart = CartModel::find($cartId);
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Produk Tidak Ditemukan',
            ], 404);
        }
        CartModel::where([
            'user_id' => auth()->id(),
            'id' => $cartId,
        ])->update([
            'jumlah' => $jumlah,
        ]);
        $getCarts = CartModel::where('user_id', auth()->id())->get();
        $datas = [];
        foreach ($getCarts as $cart) {
            $datas[] = [
                'label' => $cart->produk->label,
                'harga' => $cart->produk->harga,
                'jumlah' => $cart->jumlah,
            ];
        }
        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Diupdate',
            'data' => $datas,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function invoice($invoice = null)
    {
        $orders = OrdersModel::where([
            'user_id' => auth()->id(),
            'no_invoice' => $invoice,
        ])->first();
        $carts = CartModel::where('user_id', auth()->id())->get();
        return view("store.content.invoice", compact("carts", "orders"));
    }

    /**
     * Display the specified resource.
     */
    public function checkout()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        $items = [];
        $generateInvoice = 'INV-WG-' . time();
        foreach ($carts as $cart) {
            $items[] = [
                'id' => $cart->produk->id,
                'name' => $cart->produk->label,
                'jumlah' => $cart->jumlah,
                'harga' => $cart->produk->harga,
                'total' => $cart->produk->harga * $cart->jumlah,
            ];
            $itemDetails[] = [
                'id' => 'WG-'.$cart->produk->id,
                'price' => $cart->produk->harga,
                'quantity' => $cart->jumlah,
                'name' => $cart->produk->label
            ];
        }
        $totalBayar = array_sum(array_column($items, 'total'));
        $transactionData = [
            'transaction_details' => [
                'order_id' => $generateInvoice,
                'gross_amount' => $totalBayar, // Uang yang dibayar
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name'    => auth()->user()->first_name,
                'last_name'     => auth()->user()->last_name,
                'email'         => auth()->user()->email,
                'phone'         => "",
            ],
        ];
        // dd($transactionData);

        try {
            $snapToken = Snap::getSnapToken($transactionData);
            OrdersModel::create([
                'user_id' => auth()->id(),
                'no_invoice' => $generateInvoice,
                'items' => json_encode($items),
                'total' => $totalBayar,
                'snap_token' => $snapToken,
            ]);
            CartModel::where('user_id', auth()->id())->delete();
            return redirect()->route('order-details', ['invoice' => $generateInvoice]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function updateorders(Request $request)
    {
        if ($request->data['transaction_status'] == "pending") {
            OrdersModel::where([
                'user_id' => auth()->id(),
                'no_invoice' => $request->no_invoice,
            ])->update([
                'status' => 1,
            ]);
        } else if ($request->data['transaction_status'] == "settlement") {
            OrdersModel::where([
                'user_id' => auth()->id(),
                'no_invoice' => $request->no_invoice,
            ])->update([
                'status' => 2,
            ]);
        }
        $data = [
            'va_number' => $request->data['bca_va_number'],
            'total' => $request->data['gross_amount'],
            'order_id' => $request->data['order_id'],
            'transaction_id' => $request->data['transaction_id'],
            'invoice' => $request->no_invoice,
            'transaction_status' => $request->data['transaction_status'],
            'payment_type' => $request->data['payment_type'],
            'payment_code' => $request->data['payment_code'] ?? null,
            'bank' => $request->data['bank'] ?? null,
            ''
        ];
        OrdersModel::where([
            'user_id' => auth()->id(),
            'no_invoice' => $request->no_invoice,
        ])->update([
            'data_midtrans' => json_encode($data),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Diperbarui',
            'data' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
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
     * Update the specified resource in storage.
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
        // dd($request->password);
        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['password' => 'Password Lama saat ini tidak sesuai.']);
        }
        return redirect()->back()->with('alert', [
            'title' => 'Berhasil',
            'message' => 'Profile berhasil diperbarui',
            'type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
