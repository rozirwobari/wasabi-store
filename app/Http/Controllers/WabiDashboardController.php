<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WabiDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function admin()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['admin', 'superadmin'])) {
                return redirect()->route('dashboards.home');
            } else {
                return redirect('/');
            }
        }
        return view('dashboard.auth.content.login');
    }

    /**
     * Display a listing of the resource.
     */
    public function authadmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if (in_array($user->role, ['admin', 'superadmin'])) {
                    Auth::login($user, $request->has('remember'));
                    return redirect()->route('dashboards.home');
                } else {
                    return redirect('/');
                }
            } else {
                return back()->withErrors(['password' => 'Password Salah.'])->withInput();
            }
        }
        return back()->withErrors(['email' => 'Email Tidak Ditemukan.'])->withInput();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        return view('dashboard.content.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function kategori()
    {
        $kategoris = KategoriModel::all();
        return view('dashboard.content.kategori', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function produk()
    {
        $produks = ProdukModel::all();
        return view('dashboard.content.produk', compact('produks'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function tambahproduk()
    {
        $kategoris = KategoriModel::all();
        return view('dashboard.content.addproduk', compact('kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function saveproduk(Request $request)
    {
        $imagePaths = [];
        if ($request->hasFile('images')) {
            $uploadPath = public_path('images/products');
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
        $deskripsi = $request->deskripsi;
        ProdukModel::create([
            'kategori_id' => $kategori,
            'label' => $produk_name,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
            'images' => json_encode($imagePaths),
        ]);
        return redirect()->route('dashboards.produk')->with('alert', [
                'title' => 'Berhasil',
                'text' => "Berhasil Menyimpan Produk ".$produk_name,
                'type' => "success"
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function editproduk($id)
    {
        $produk = ProdukModel::find($id);
        $kategoris = KategoriModel::all();
        return view('dashboard.content.editproduk', compact('produk', 'kategoris'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function updateproduk(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:wabi-produk,id',
            'produk_name' => 'required|string|max:255',
            'kategori' => 'required|exists:wabi-kategori,id',
            'harga' => 'required|numeric|min:1000',
            'deskripsi' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
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
                    // Find position of deleted image
                    $deletePosition = array_search($imagePath, $finalImages);
                    if ($deletePosition !== false) {
                        $finalImages[$deletePosition] = null;
                    }
                    
                    if (file_exists(public_path($imagePath))) {
                        unlink(public_path($imagePath));
                    }
                }
            }
        }
        
        for ($i = 0; $i < 5; $i++) {
            if ($request->hasFile("images.{$i}")) {
                $file = $request->file("images.{$i}");
                
                // Generate unique filename
                $timestamp = now()->timestamp;
                $randomString = \Str::random(10);
                $extension = $file->getClientOriginalExtension();
                $filename = $timestamp . '_' . $randomString . '.' . $extension;
                
                // Store file
                $path = 'images/products/' . $filename;
                $file->move(public_path('images/products'), $filename);
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
            'deskripsi' => $request->deskripsi,
            'images' => json_encode($finalImages)
        ]);

        if (empty($finalImages)) {
            return back()->withErrors(['images' => 'Minimal 1 gambar produk harus ada.'])->withInput();
        }
        return redirect()->route('dashboards.produk')->with('alert', [
            'title' => 'Berhasil',
            'text' => "Produk Berhasil Diupdate ".$request->produk_name,
            'type' => "success"
        ]);
    }
}
