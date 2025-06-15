<?php

namespace App\Http\Controllers;

use App\Models\WabiMidtrans;
use Illuminate\Http\Request;

use App\Models\OrdersModel;
use Illuminate\Support\Facades\Log;

class WabiMidtransController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function callback(Request $request)
    {
        // Ambil server key dari config
        $serverKey = config('midtrans.server_key');
        
        // Ambil data dari callback
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;
        
        // Verifikasi signature key
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($signatureKey !== $expectedSignature) {
            Log::error('Invalid signature from Midtrans callback, '.$signatureKey." expectedSignature : ".$expectedSignature);
            return response()->json(['status' => 'error'], 403);
        }
        
        // Proses berdasarkan transaction status
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? null;
        
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'challenge') {
                    // Transaksi di-challenge, perlu review manual
                    $this->updateOrderStatus($orderId, 'challenge');
                } else if ($fraudStatus == 'accept') {
                    // Transaksi berhasil
                    $this->updateOrderStatus($orderId, 'success');
                }
                break;
                
            case 'settlement':
                // Transaksi berhasil (untuk non-card payment)
                $this->updateOrderStatus($orderId, 'success');
                break;
                
            case 'pending':
                // Transaksi pending
                $this->updateOrderStatus($orderId, 'pending');
                break;
                
            case 'deny':
                // Transaksi ditolak
                $this->updateOrderStatus($orderId, 'failed');
                break;
                
            case 'expire':
                // Transaksi expired
                $this->updateOrderStatus($orderId, 'expired');
                break;
                
            case 'cancel':
                // Transaksi dibatalkan
                $this->updateOrderStatus($orderId, 'cancelled');
                break;
        }
        
        // Log callback untuk debugging
        Log::info('Midtrans callback received', $request->all());

        $orders = OrdersModel::firstWhere('no_invoice', $orderId);
        if ($orders) {
            $orders->update([
                'status' => 2,
                'data_midtrans' => json_decode($request->all())
            ]);
        }
        return response()->json(['status' => 'ok']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WabiMidtrans $wabiMidtrans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WabiMidtrans $wabiMidtrans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WabiMidtrans $wabiMidtrans)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WabiMidtrans $wabiMidtrans)
    {
        //
    }
}
