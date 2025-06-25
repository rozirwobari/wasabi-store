<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProdukModel;
use App\Models\User;

class CartModel extends Model
{
    protected $table = 'wabi_cart';
    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
    ];

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}