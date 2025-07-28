<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use App\Http\Controllers\WabiApiController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WabiAdminProduk
{
    public function produk()
    {
        $produks = ProdukModel::all();
        $kategoris = KategoriModel::all();
        return view('dashboard.content.produk', compact('produks', 'kategoris'));
    }

    public function tambahproduk()
    {
        $WabiApiController = new WabiApiController();
        $kategoris = KategoriModel::all();
        $GetItems = $WabiApiController->GetItemGame();
        if (!$GetItems['success']) {
            return redirect()->back()->with('alert', [
                'title' => $GetItems['message'],
                'text' => $GetItems['error'],
                'type' => $GetItems['status']
            ]);
        }
        $items = json_decode($GetItems['data'], false)->data;
        return view('dashboard.content.addproduk', compact('kategoris', 'items'));
    }

    public function editproduk($id)
    {
        $WabiApiController = new WabiApiController();
        $produk = ProdukModel::find($id);
        $kategoris = KategoriModel::all();
        $GetItems = $WabiApiController->GetItemGame();
        if ($GetItems['success']) {
            $itemsData = json_decode($GetItems['data'], false);
            if ($itemsData->success) {
                $items = $itemsData->data;
                return view('dashboard.content.editproduk', compact('produk', 'kategoris', 'items'));
            }
        }
        return redirect()->back()->with('alert', [
            'title' => 'Error',
            'text' => "Berhasil Menyimpan Produk ",
            'type' => "error"
        ]);
    }

    public function saveproduk(Request $request)
    {
        $imagePaths = [];
        if ($request->hasFile('images')) {
            $uploadPath = 'images/products';
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $fileName);
                $imagePaths[] = 'images/products/' . $fileName;
            }
        }
        $produk_name = $request->produk_name;
        $kategori = $request->kategori;
        $harga = $request->harga;
        $name_item = $request->name_item;
        $deskripsi = $request->deskripsi;
        ProdukModel::create([
            'kategori_id' => $kategori,
            'label' => $produk_name,
            'name_item' => $name_item,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
            'images' => json_encode($imagePaths),
        ]);
        return redirect()->route('admin.produk')->with('alert', [
                'title' => 'Berhasil',
                'text' => "Berhasil Menyimpan Produk ".$produk_name,
                'type' => "success"
            ]);
    }

    public function updateproduk(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:wabi_produk,id',
            'produk_name' => 'required|string|max:255',
            'name_item' => 'required|string|max:255',
            'kategori' => 'required|exists:wabi_kategori,id',
            'harga' => 'required|numeric|min:1000',
            'deskripsi' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200', // 50MB
            'deleted_images' => 'nullable|string',
            'main_image_index' => 'nullable|integer|min:0|max:4'
        ]);

        $produk = ProdukModel::findOrFail($request->produk_id);
        $existingImages = json_decode($produk->images, true) ?? [];
        $finalImages = array_fill(0, 5, null);
        foreach ($existingImages as $index => $imagePath) {
            if ($index < 5) {
                $finalImages[$index] = $imagePath;
            }
        }

        if ($request->deleted_images) {
            $deletedImagePaths = json_decode($request->deleted_images, true);

            if (is_array($deletedImagePaths)) {
                foreach ($deletedImagePaths as $imagePath) {
                    $deletePosition = array_search($imagePath, $finalImages);
                    if ($deletePosition !== false) {
                        $finalImages[$deletePosition] = null;
                    }
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }
        }

        for ($i = 0; $i < 5; $i++) {
            if ($request->hasFile("images.{$i}")) {
                $file = $request->file("images.{$i}");
                $timestamp = now()->timestamp;
                $randomString = Str::random(10);
                $extension = $file->getClientOriginalExtension();
                $filename = $timestamp . '_' . $randomString . '.' . $extension;
                $path = 'images/products/' . $filename;
                $file->move('images/products', $filename);
                $finalImages[$i] = $path;
            }
        }

        $finalImages = array_values(array_filter($finalImages, function($value) {
            return $value !== null;
        }));

        $mainImageIndex = $request->main_image_index ?? 0;
        if ($mainImageIndex >= count($finalImages)) {
            $mainImageIndex = 0;
        }

        if ($mainImageIndex > 0 && isset($finalImages[$mainImageIndex])) {
            $mainImage = $finalImages[$mainImageIndex];
            unset($finalImages[$mainImageIndex]);
            array_unshift($finalImages, $mainImage);
            $finalImages = array_values($finalImages);
        }

        $produk->update([
            'label' => $request->produk_name,
            'kategori_id' => $request->kategori,
            'harga' => $request->harga,
            'name_item' => $request->name_item,
            'deskripsi' => $request->deskripsi,
            'images' => json_encode($finalImages)
        ]);

        if (empty($finalImages)) {
            return back()->withErrors(['images' => 'Minimal 1 gambar produk harus ada.'])->withInput();
        }
        return redirect()->route('admin.produk')->with('alert', [
            'title' => 'Berhasil',
            'text' => "Produk Berhasil Diupdate ".$request->produk_name,
            'type' => "success"
        ]);
    }

    public function hapusproduk(Request $request)
    {
        $id = $request->input('produk_id');
        $produks = ProdukModel::where('id', $id)->first();
        if (!$produks) {
            $images = json_decode($produks->images);
            if (count($images) > 0 ) {
                foreach ($images as $key => $value) {
                    if (file_exists($value)) {
                        unlink($value);
                    }
                }
            }
            return response()->json([
                'title' => 'Gagal',
                'text' => 'Produk tidak ditemukan.',
                'type' => 'error',
            ], 404);
        }
        $produks->delete();
        return response()->json([
            'title' => 'Berhasil',
            'text' => 'Produk berhasil dihapus.',
            'type' => 'success',
        ], 200);
    }
}
