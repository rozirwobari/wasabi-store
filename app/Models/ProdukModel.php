<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriModel;

class ProdukModel extends Model
{
    protected $table = 'wabi_produk';
    protected $guarded = [];

    public function kategoris()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id'); // 'kategori_id' adalah foreign key di tabel produk
    }
}