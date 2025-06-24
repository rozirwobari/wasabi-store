<?php

namespace App\Http\Controllers;

use App\Models\WabiMidtrans;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

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
        $orderId = $request->order_id ?? $request->no_invoice ?? null;
        $statusCode = $request->status_code ?? $request->data['status_code'] ?? null;
        $grossAmount = $request->gross_amount ?? $request->data['gross_amount'] ?? null;
        $signatureKey = $request->signature_key ?? $request->data['signature_key'] ?? null;
        
        // Verifikasi signature key
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($signatureKey !== $expectedSignature) {
            Log::error('Invalid signature from Midtrans callback, '.$signatureKey." expectedSignature : ".$expectedSignature);
            return response()->json(['status' => 'error'], 403);
        }
        
        // Proses berdasarkan transaction status
        $transactionStatus = $request->transaction_status ?? $request->data['no_invoice'] ?? null;
        $fraudStatus = ($request->fraud_status ?? $request->data['fraud_status']) ?? null;
        $status_code = 0;
        $reason = null;
        $orders = OrdersModel::firstWhere('no_invoice', $orderId);
        $tgl_transaksi = json_decode($orders->tgl_transaksi, true);
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'challenge') {
                    $status_code = 2;
                    $tgl_transaksi["2"] = time();
                } else if ($fraudStatus == 'accept') {
                    $status_code = 2;
                    $tgl_transaksi["2"] = time();
                }
                break;
            case 'settlement':
                $status_code = 2;
                $tgl_transaksi["2"] = time();
                break;
                
            case 'pending':
                $status_code = 1;
                $tgl_transaksi["1"] = time();
                break;
                
            case 'deny':
                $status_code = 404;
                $tgl_transaksi["404"] = time();
                $reason = "Pembayaran Ditolak";
                break;
                
            case 'expire':
                $status_code = 404;
                $tgl_transaksi["404"] = time();
                $reason = "Pembayaran Kadaluarsa";
                break;
                
            case 'cancel':
                $status_code = 404;
                $tgl_transaksi["404"] = time();
                $reason = "Pembayaran Dibatalkan";
                break;
        }

        Log::info('Midtrans callback received', $request->all());
        if ($orders) {
            $orders->update([
                'status' => $status_code,
                'data_midtrans' => ($reason ?? json_encode($request->data)),
                'tgl_transaksi' => json_encode($tgl_transaksi),
            ]);
            if ($status_code >= 2) {
                $tgl_transaksi["3"] = time();
                $tgl_transaksi["4"] = time();
                $this->testSendData();
                $orders->update([
                    'status' => 4,
                    'data_midtrans' => ($reason ?? json_encode($request->data)),
                    'tgl_transaksi' => json_encode($tgl_transaksi),
                ]);
            }
        }
        return response()->json(['status' => 'ok'], 200);
    }














    // Menigirim Data Ke Game Server
    private function CreateSignature($data)
    {
        $jsonString = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return hash_hmac('sha256', $jsonString, "8L5MdvnIT6NVXZE2mbqxXMalDGuFGsBG");
    }

    private function testSendData()
    {
        $nodeJsUrl = "208.76.40.92:2003/api/proses";
        try {
            // Data sample untuk testing
            $sampleData = [
                'order_id' => 999,
                'name_item' => 'Test User',
                'steam_hex' => 'steam:asdasin1320123asd',
                'timestamp' => time()
            ];

            $signature = $this->CreateSignature($sampleData);

            $payload = [
                'data' => $sampleData,
                'signature' => $signature
            ];

            // Kirim ke Node.js
            $response = Http::timeout(10)->post($nodeJsUrl, $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Test berhasil!',
                    'sent_payload' => $payload,
                    'nodejs_response' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error 1',
                    'message' => 'Test gagal!',
                    'error' => $response->body(),
                    'sent_payload' => $payload
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error 2',
                'message' => 'Test error!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
