<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WabiDashboardAdmin;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\WabiHome;
use App\Http\Controllers\WabiCart;
use App\Http\Controllers\WabiDashboardUser;
use App\Http\Controllers\WabiMidtransController;


// Auth
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Home
Route::get('/', [WabiHome::class, 'index']);
Route::get('/order-details/{invoice}', [WabiHome::class, 'orderdetails'])->name('order-details');
Route::get('/produk-details/{id}', [WabiHome::class, 'produkdetail'])->name('produk-details');


Route::middleware('auth')->group(function () {
    // Keranjang
    Route::get('/cart', [WabiCart::class, 'cart']);
    Route::post('/checkout', [WabiCart::class, 'checkout'])->name('checkout');
    Route::post('/addtocarts', [WabiCart::class, 'addtocart'])->name('addtocarts');
    Route::post('/deletecarts', [WabiCart::class, 'deletecarts'])->name('deletecarts');
    Route::post('/updatecarts', [WabiCart::class, 'updatecarts'])->name('updatecarts');

    // Dashboard User
    Route::get('/dashboard', [WabiDashboardUser::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [WabiDashboardUser::class, 'profile'])->name('profile');
    Route::get('/orders', [WabiDashboardUser::class, 'orders'])->name('orders');
    Route::get('/dataplayers', [WabiDashboardUser::class, 'dataplayers'])->name('dataplayers');
    Route::get('/settings', [WabiDashboardUser::class, 'settings'])->name('settings');
    Route::post('/profileupdate', [WabiDashboardUser::class, 'profileupdate'])->name('profileupdate');
    Route::post('/changepassword', [WabiDashboardUser::class, 'changepassword'])->name('changepassword');
    Route::post('/getplayerdata', [WabiDashboardUser::class, 'GetPlayerData'])->name('getplayerdata');
    Route::post('/saveplayerdata', [WabiDashboardUser::class, 'SavePlayerData'])->name('saveplayerdata');
    Route::post('/updateplayerdata', [WabiDashboardUser::class, 'updateplayerdata'])->name('updateplayerdata');
    Route::post('/deleteplayerdata', [WabiDashboardUser::class, 'deleteplayerdata'])->name('deleteplayerdata');
});


// Route::get('/testSendData', [WabiMidtransController::class, 'testSendData'])->name('testSendData');










Route::get('/admin', [WabiDashboardAdmin::class, 'admin'])->name('admin');
Route::post('/authadmin', [WabiDashboardAdmin::class, 'authadmin'])->name('authadmin');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [WabiDashboardAdmin::class, 'index'])->name('home');
    // Kategori
    Route::get('/kategori', [WabiDashboardAdmin::class, 'kategori'])->name('kategori');
    Route::post('/hapuskategori', [WabiDashboardAdmin::class, 'hapuskategori'])->name('hapuskategori');
    Route::post('/tambahkategori', [WabiDashboardAdmin::class, 'tambahkategori'])->name('tambahkategori');
    Route::post('/editkategori', [WabiDashboardAdmin::class, 'editkategori'])->name('editkategori');
    // Produk
    Route::get('/produk', [WabiDashboardAdmin::class, 'produk'])->name('produk');
    Route::get('/tambahproduk', [WabiDashboardAdmin::class, 'tambahproduk'])->name('tambahproduk');
    Route::get('/editproduk/{id}', [WabiDashboardAdmin::class, 'editproduk'])->name('editproduk');
    Route::post('/editproduk', [WabiDashboardAdmin::class, 'saveeditproduk'])->name('saveeditproduk');
    Route::post('/saveproduk', [WabiDashboardAdmin::class, 'saveproduk'])->name('saveproduk');
    Route::post('/hapusproduk', [WabiDashboardAdmin::class, 'hapusproduk'])->name('hapusproduk');
    Route::post('/updateproduk', [WabiDashboardAdmin::class, 'updateproduk'])->name('updateproduk');
    // Pengguna
    Route::get('/pengguna', [WabiDashboardAdmin::class, 'pengguna'])->name('pengguna');
    Route::get('/editpengguna', [WabiDashboardAdmin::class, 'editpengguna'])->name('editpengguna');
    Route::post('/savepengguna', [WabiDashboardAdmin::class, 'savepengguna'])->name('savepengguna');
    Route::post('/hapuspengguna', [WabiDashboardAdmin::class, 'hapuspengguna'])->name('hapuspengguna');
});