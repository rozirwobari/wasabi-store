<?php

namespace App\Http\Controllers;

use App\Models\WabiMidtrans;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

use App\Models\OrdersModel;
use App\Models\WabiGameProfile;
use Illuminate\Support\Facades\Log;

class WabiApiController
{
    protected $gameEndpoint;
    public function __construct()
    {
        // $this->gameEndpoint = "http://api.yumeroleplay.my.id/";
        $this->gameEndpoint = "http://208.76.40.92/";
    }
    /**
     * Display a listing of the resource.
     */
    public function MidtransCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $request->order_id ?? $request->no_invoice ?? null;
        $statusCode = $request->status_code ?? $request->data['status_code'] ?? null;
        $grossAmount = $request->gross_amount ?? $request->data['gross_amount'] ?? null;
        $signatureKey = $request->signature_key ?? $request->data['signature_key'] ?? null;

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            return response()->json([
                'success' => false,
                'message' => 'Signature Tidak Cocok'
            ], 403);
        }

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

        if ($orders) {
            $orders->update([
                'status' => $status_code,
                'data_midtrans' => ($reason ?? json_encode($request->data)),
                'tgl_transaksi' => json_encode($tgl_transaksi),
            ]);
            if ($status_code >= 2) {
                $tgl_transaksi["3"] = time();
                $orders->update([
                    'status' => 3,
                    'data_midtrans' => ($reason ?? json_encode($request->data)),
                    'tgl_transaksi' => json_encode($tgl_transaksi),
                ]);
                $responGame = $this->SendDataToGame([
                    'order_id' => $orderId,
                    'steam_hex' => $orders->identifier,
                    'email' => $orders->user->email,
                    'data_items' => $orders->items,
                ]);
                if ($responGame) {
                    $tgl_transaksi["4"] = time();
                    $orders->update([
                        'status' => 4,
                        'data_midtrans' => ($reason ?? json_encode($request->data)),
                        'tgl_transaksi' => json_encode($tgl_transaksi),
                    ]);
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Terupdate'
        ], 200);
    }

    public function GetItemGame()
    {
        try {
            $response = Http::timeout(10)->post($this->gameEndpoint.'api/getitems', []);
            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => 'success',
                    'data' => $response->body(),
                ];
            } else {
                return [
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Error',
                    'error' => $response->body(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Error',
                'error' => $e->getMessage(),
            ];
        }
    }

    // Menigirim Data Ke Game Server
    private function CreateSignature($data)
    {
        $jsonString = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return hash_hmac('sha256', $jsonString, "8L5MdvnIT6NVXZE2mbqxXMalDGuFGsBG");
    }

    private function SendDataToGame($data)
    {
        try {
            $signature = $this->CreateSignature($data);
            $payload = [
                'data' => $data,
                'signature' => $signature
            ];
            $response = Http::timeout(10)->post($this->gameEndpoint.'api/proses', $payload);
            if ($response->successful()) {
                response()->json([
                    'status' => 'success',
                    'message' => 'Test berhasil!',
                    'sent_payload' => $payload,
                    'nodejs_response' => $response->json()
                ]);
                return true;
            } else {
                response()->json([
                    'status' => 'error',
                    'message' => 'Test gagal!',
                    'error' => $response->body(),
                    'sent_payload' => $payload
                ], 500);
                return false;
            }

        } catch (\Exception $e) {
            response()->json([
                'status' => 'error 2',
                'message' => 'Test error!',
                'error' => $e->getMessage()
            ], 500);
            return false;
        }
    }

    public function updatepesanan(Request $request)
    {
        if ($request->success) {
            if ($request->status == 'pengiriman') {
                $orders = OrdersModel::firstWhere('no_invoice', $request->order_id);
                if ($orders) {
                    $get_tgl_transaksi = json_decode($orders->tgl_transaksi, true);
                    $get_tgl_transaksi["4"] = time();
                    $orders->update([
                        'status' => 4,
                        'reason_game' => json_encode([
                            'pengiriman' => $request->message
                        ]),
                        'tgl_transaksi' => json_encode($get_tgl_transaksi),
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Berhasil Update Data',
                    ], 200);
                }
            }
            if ($request->status == 'claim_item') {
                $orders = OrdersModel::firstWhere('no_invoice', $request->order_id);
                if ($orders) {
                    $get_tgl_transaksi = json_decode($orders->tgl_transaksi, true);
                    $get_tgl_transaksi["5"] = time();
                    $reason_games = json_decode($orders->reason_game, true);
                    if (!($reason_games && isset($reason_games['claim_item']))) {
                        $reason_games['claim_item'] = [];
                    }
                    array_push($reason_games['claim_item'], $request->message);
                    $orders->update([
                        'status' => 5,
                        'reason_game' => json_encode($reason_games),
                        'tgl_transaksi' => json_encode($get_tgl_transaksi),
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Berhasil Update Data',
                    ], 200);
                }
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Transaksi Tidak Ditemukan',
        ], 404);
    }

    public function GetPlayerData($data)
    {
        $response = Http::post($this->gameEndpoint.'api/getdataplayer', $data);
        if ($response->successful()) {
            return $response->json();
        }
        return false;
    }

    public function LinkedAccount($data)
    {
        $response = Http::post($this->gameEndpoint.'api/linkaccount', $data);
        if ($response->successful()) {
            return $response->json();
        }
        return false;
    }

    public function linkedRespon(Request $request)
    {
        $user_id = $request->user_id;
        $identifier = $request->identifier;
        $PlayerData = $request->playerdata;
        $status = $request->status;
        if ($request->action == 'removelinked') {
            $GameProfile = WabiGameProfile::where('user_id', $user_id)->where('identifier', $identifier)->first();
            if ($GameProfile) {
                $GameProfile->delete();
                return response()->json([
                    'success' => true,
                    'message' => "Data Berhasil Dihapus"
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => "Data Tidak Ditemukan"
            ], 200);
        }

        $GameProfile = WabiGameProfile::where('user_id', $user_id)->where('identifier', $identifier)->first();
        if ($GameProfile) {
            $GameProfile->status = $status;
            $GameProfile->updated_at = now();
            $GameProfile->save();
            return response()->json([
                'success' => true,
                'message' => "Data Berhasil Diupdate"
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Data Tidak Ditemukan"
            ], 500);
        }
    }
}
