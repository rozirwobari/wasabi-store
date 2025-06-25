<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'tgl_transaksi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}