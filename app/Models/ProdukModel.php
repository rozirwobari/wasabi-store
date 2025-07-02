<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriModel;

class ProdukModel extends Model
{
    protected $table = 'wabi_produk';
    protected $fillable = [
        'kategori_id',
        'label',
        'name_item',
        'deskripsi',
        'harga',
        'images',
    ];

    public function kategoris()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id');
    }
}