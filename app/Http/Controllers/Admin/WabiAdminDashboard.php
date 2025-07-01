<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ProdukModel;
use App\Models\OrdersModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WabiAdminDashboard
{
    public function index()
    {
        $orders = OrdersModel::orderBy('created_at', 'desc')->get();
        $produks = ProdukModel::orderBy('created_at', 'desc')->get();
        $WabiAdminOrders = new WabiAdminOrders();        
        $getPendapatan = $WabiAdminOrders->GetTotalPendapatan();
        $persetasiPendapatan = $WabiAdminOrders->hitungPersentase(
            $getPendapatan['bulanLalu'], 
            $getPendapatan['bulanIni'],
        );

        $getPendapatan = $WabiAdminOrders->GetTotalOrder();
        $persetasiOrders = $WabiAdminOrders->hitungPersentase(
            $getPendapatan['bulanLalu'], 
            $getPendapatan['bulanIni'],
        );

        $totalTerjual = 0;
        foreach ($orders as $order) {
            $item = json_decode($order->items);
            $totalTerjual += count($item);
        }

        return view('dashboard.content.index', compact('orders', 'persetasiOrders', 'persetasiPendapatan', 'totalTerjual', 'produks'));
    }
}
