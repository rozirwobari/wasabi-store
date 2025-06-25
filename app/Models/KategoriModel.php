<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProdukModel;

class KategoriModel extends Model
{
    protected $table = 'wabi_kategori';
    protected $fillable = [
        'label',
    ];

    public function produk()
    {
        return $this->hasMany(ProdukModel::class, 'kategori_id');
    }
}