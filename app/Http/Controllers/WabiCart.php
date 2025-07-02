<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProdukModel;
use App\Models\CartModel;
use App\Models\OrdersModel;
use App\Models\WabiGameProfile;

use Midtrans\Config;
use Midtrans\Snap;

class WabiCart
{

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        if (!Config::$serverKey) {
            throw new \Exception('Midtrans Server Key tidak ditemukan. Periksa file .env dan config/midtrans.php');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function cart()
    {
        $carts = CartModel::where('user_id', auth()->id())->get();
        $dataPlayers = WabiGameProfile::where('user_id', auth()->id())->get();
        return view("store.content.cart", compact("carts", "dataPlayers"));
    }

    /**
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
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

    public function checkout(Request $request)
    {
        $identifier = $request->identifier;
        $carts = CartModel::where('user_id', auth()->id())->get();
        $items = [];
        $generateInvoice = 'INV-WG-' . time();
        foreach ($carts as $cart) {
            $items[] = [
                'id' => $cart->produk->id,
                'name' => $cart->produk->label,
                'name_item' => $cart->produk->name_item,
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
        try {
            $snapToken = Snap::getSnapToken($transactionData);
            OrdersModel::create([
                'user_id' => auth()->id(),
                'no_invoice' => $generateInvoice,
                'items' => json_encode($items),
                'total' => $totalBayar,
                'snap_token' => $snapToken,
                'identifier' => $identifier
            ]);
            CartModel::where('user_id', auth()->id())->delete();
            return redirect()->route('order-details', ['invoice' => $generateInvoice]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('alert', [
                'title' => 'Gagal',
                'message' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }
}
