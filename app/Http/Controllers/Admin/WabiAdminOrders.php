<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use App\Models\OrdersModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WabiAdminOrders extends Controller
{
    public function index()
    {
        $orders = OrdersModel::orderBy('created_at', 'desc')->get();

        $getPendapatan = $this->GetTotalPendapatan();
        $persetasiPendapatan = $this->hitungPersentase(
            $getPendapatan['bulanLalu'],
            $getPendapatan['bulanIni'],
        );

        $getPendapatan = $this->GetTotalOrder();
        $persetasiOrders = $this->hitungPersentase(
            $getPendapatan['bulanLalu'],
            $getPendapatan['bulanIni'],
        );

        $totalTerjual = 0;
        foreach ($orders as $order) {
            $item = json_decode($order->items);
            $totalTerjual += count($item);
        }
        return view('dashboard.content.orders', compact('orders', 'totalTerjual', 'persetasiPendapatan', 'persetasiOrders'));
    }

    public function GetTotalOrder()
    {
        $bulanIni = date('Y-m');
        $bulanLalu = date('Y-m', strtotime('-1 month'));
        $countItemBulanIni = 0;
        $countItemBulanLalu = 0;
        $pendapatanBulanIni = OrdersModel::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanIni])
            ->whereIn('status', [2, 3, 4, 5, 6])->get();
            // ->where('status', 0)->get();

        foreach ($pendapatanBulanIni as $key => $value) {
            $item = json_decode($value->items);
            $countItemBulanIni += count($item);
        };

        $pendapatanBulanLalu = OrdersModel::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanLalu])
            ->whereIn('status', [2, 3, 4, 5, 6])->get();
            // ->where('status', 0)->get();

        foreach ($pendapatanBulanLalu as $key => $value) {
            $item = json_decode($value->items);
            $countItemBulanLalu += count($item);
        };
        return [
            'bulanIni' => $countItemBulanIni,
            'bulanLalu' => $countItemBulanLalu,
        ];
    }

    public function GetTotalPendapatan()
    {
        $bulanIni = date('Y-m');
        $bulanLalu = date('Y-m', strtotime('-1 month'));
        $pendapatanBulanIni = OrdersModel::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanIni])
            ->whereIn('status', [2, 3, 4, 5, 6])
            ->sum('total');

        $pendapatanBulanLalu = OrdersModel::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanLalu])
            ->whereIn('status', [2, 3, 4, 5, 6])
            ->sum('total');
        return [
            'bulanIni' => $pendapatanBulanIni,
            'bulanLalu' => $pendapatanBulanLalu,
        ];
    }

    public function hitungPersentase($nilaiLama, $nilaiBaru)
    {
        if ($nilaiLama == 0) {
            return $nilaiBaru > 0 ? 100 : 0;
        }

        $persentase = (($nilaiBaru - $nilaiLama) / $nilaiLama) * 100;
        return round($persentase, 1);
    }

    public function show($invoice = null)
    {
        if ($invoice) {
            $orders = OrdersModel::where('no_invoice', $invoice)->first();
            if ($orders) {
                return view("dashboard.content.orderdetail", compact('orders'));
            }
        }
        return redirect()->back()->with('alert', [
                'title' => 'Gagal',
                'text' => "Invoice ".$invoice." Tidak Terdaftar",
                'type' => "warning"
            ]);
    }
}
