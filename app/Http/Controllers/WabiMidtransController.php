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

    private $secretKey = '8L5MdvnIT6NVXZE2mbqxXMalDGuFGsBG';
    private $nodeJsUrl = 'http://208.76.40.92:2003/api/proses';

    // Menigirim Data Ke Game Server
    private function CreateSignature($data)
    {
        $jsonString = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return hash_hmac('sha256', $jsonString, $this->secretKey);
    }

    public function testSendData()
    {
        try {
            // PERBAIKAN: Cek koneksi dulu sebelum kirim data
            $connectionTest = $this->testConnection();
            if (!$connectionTest['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat terhubung ke Node.js server',
                    'connection_test' => $connectionTest,
                    'troubleshooting' => [
                        'check_nodejs_server' => 'Pastikan Node.js server running di ' . $this->nodeJsUrl,
                        'check_port' => 'Cek apakah port 2003 terbuka',
                        'check_firewall' => 'Pastikan firewall tidak memblokir koneksi'
                    ]
                ], 500);
            }

            // Data sample untuk testing
            $sampleData = [
                'order_id' => 999,
                'name_item' => 'Test User',
                'steam_hex' => 'steam:asdasin1320123asd',
                'timestamp' => time(),
                'test_message' => 'Hello from Laravel!'
            ];

            $signature = $this->createSignature($sampleData);

            $payload = [
                'data' => $sampleData,
                'signature' => $signature
            ];

            // Log untuk debugging
            Log::info('Mengirim test data ke Node.js:', [
                'url' => $this->nodeJsUrl,
                'payload' => $payload
            ]);

            // PERBAIKAN: Tambahkan headers dan error handling yang lebih baik
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->retry(2, 1000) // Retry 2x dengan delay 1 detik
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'Laravel-NodeJS-Client/1.0'
                ])
                ->post($this->nodeJsUrl, $payload);

            // Cek response sukses
            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Response sukses dari Node.js:', $responseData);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Test berhasil! Data terkirim ke Node.js',
                    'sent_payload' => $payload,
                    'nodejs_response' => $responseData,
                    'response_time' => $response->transferStats?->getTransferTime(),
                    'status_code' => $response->status()
                ]);
            } else {
                // Response tidak sukses (4xx, 5xx)
                Log::error('Response error dari Node.js:', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Node.js server mengembalikan error',
                    'error_type' => 'http_error',
                    'status_code' => $response->status(),
                    'error_response' => $response->body(),
                    'sent_payload' => $payload
                ], $response->status());
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Error koneksi (tidak bisa connect ke server)
            Log::error('Connection error ke Node.js:', [
                'error' => $e->getMessage(),
                'url' => $this->nodeJsUrl
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat terhubung ke Node.js server',
                'error_type' => 'connection_error',
                'error_details' => $e->getMessage(),
                'server_url' => $this->nodeJsUrl,
                'troubleshooting' => [
                    'step_1' => 'Cek apakah Node.js server running: curl ' . $this->nodeJsUrl,
                    'step_2' => 'Cek port: telnet 208.76.40.92 2003',
                    'step_3' => 'Cek firewall dan security group',
                    'step_4' => 'Pastikan Node.js bind ke 0.0.0.0:2003, bukan localhost:2003'
                ]
            ], 500);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Error HTTP request
            Log::error('Request error ke Node.js:', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error saat mengirim request ke Node.js',
                'error_type' => 'request_error',
                'error_details' => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Error tidak terduga
            Log::error('Unexpected error saat test send data:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi error tidak terduga',
                'error_type' => 'unexpected_error',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }











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
}
