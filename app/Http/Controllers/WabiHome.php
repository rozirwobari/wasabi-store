<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProdukModel;
use App\Models\CartModel;
use App\Models\KategoriModel;
use App\Models\OrdersModel;

class WabiHome
{
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
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
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
}
