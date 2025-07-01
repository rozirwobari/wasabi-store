<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WabiAdminKategori
{
    public function index()
    {
        $kategoris = KategoriModel::all();
        return view('dashboard.content.kategori', compact('kategoris'));
    }

    public function hapuskategori(Request $request)
    {
        $id = $request->input('kategori_id');
        $kategoris = KategoriModel::where('id', $id)->first();
        if (!$kategoris) {
            return response()->json([
                'title' => 'Gagal',
                'text' => 'Kategori tidak ditemukan.',
                'type' => 'error',
            ], 404);
        }
        $kategoris->delete();
        return response()->json([
            'title' => 'Berhasil',
            'text' => 'Kategori berhasil dihapus.',
            'type' => 'success',
        ], 200);
    }

    public function tambahkategori(Request $request)
    {
        $kategori = $request->input('kategori_name');
        KategoriModel::create([
            'label' => $kategori,
        ]);
        return response()->json([
            'title' => 'Berhasil',
            'text' => 'Kategori berhasil Ditambah.',
            'type' => 'success',
        ], 200);
    }

    public function editkategori(Request $request)
    {
        $id = $request->input('kategori_id');
        $name = $request->input('kategori_name');
        $kategoris = KategoriModel::where('id', $id)->first();
        if ($kategoris) {
            $kategoris->label = $name;
            $kategoris->save();
            return response()->json([
                'title' => 'Berhasil',
                'text' => 'Kategori Berhasil Dirubah.',
                'type' => 'success',
            ], 200);
        } else {
            return response()->json([
                'title' => 'Gagal',
                'text' => 'Kategori tidak ditemukan.',
                'type' => 'error',
            ], 404);
        }
    }
}
