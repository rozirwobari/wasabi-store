<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WabiGameProfile;

class OrdersModel extends Model
{
    protected $table = 'wabi_orders';
    protected $fillable = [
        'no_invoice',
        'user_id', 
        'items',
        'total',
        'status',
        'snap_token',
        'data_midtrans',
        'tgl_transaksi',
        'identifier',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function playerData()
    {
        return $this->belongsTo(WabiGameProfile::class, 'identifier', 'identifier');
    }
}