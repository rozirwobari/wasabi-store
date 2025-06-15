<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProdukModel;

class CartModel extends Model
{
    protected $table = 'wabi_cart';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id');
    }
}