<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WabiDashboardController;

use App\Http\Controllers\WabiHome;
use App\Http\Controllers\WabiCart;
use App\Http\Controllers\WabiDashboardUser;

Auth::routes();


// Home
Route::get('/', [WabiHome::class, 'index']);
Route::get('/order-details/{invoice}', [WabiHome::class, 'orderdetails'])->name('order-details')->middleware('auth');
Route::get('/produk-details/{id}', [WabiHome::class, 'produkdetail'])->name('produk-details');

// Keranjang
Route::get('/cart', [WabiCart::class, 'cart'])->middleware('auth');
Route::get('/checkout', [WabiCart::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/addtocarts', [WabiCart::class, 'addtocart'])->name('addtocarts')->middleware('auth');
Route::post('/deletecarts', [WabiCart::class, 'deletecarts'])->name('deletecarts')->middleware('auth');
Route::post('/updatecarts', [WabiCart::class, 'updatecarts'])->name('updatecarts')->middleware('auth');

// Dashboard User
Route::get('/dashboard', [WabiDashboardUser::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/profile', [WabiDashboardUser::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/orders', [WabiDashboardUser::class, 'orders'])->name('orders')->middleware('auth');
Route::get('/settings', [WabiDashboardUser::class, 'settings'])->name('settings')->middleware('auth');
Route::post('/profileupdate', [WabiDashboardUser::class, 'profileupdate'])->name('profileupdate')->middleware('auth');
Route::post('/changepassword', [WabiDashboardUser::class, 'changepassword'])->name('changepassword')->middleware('auth');

Route::get('/testSendData', [WabiMidtransController::class, 'testSendData'])->name('testSendData');










Route::get('/admin', [WabiDashboardController::class, 'admin'])->name('admin');
Route::post('/authadmin', [WabiDashboardController::class, 'authadmin'])->name('authadmin');
Route::prefix('dashboards')->name('dashboards.')->group(function () {
    Route::get('/', [WabiDashboardController::class, 'index'])->name('home');
    // Kategori
    Route::get('/kategori', [WabiDashboardController::class, 'kategori'])->name('kategori');
    Route::post('/hapuskategori', [WabiDashboardController::class, 'hapuskategori'])->name('hapuskategori');
    Route::post('/tambahkategori', [WabiDashboardController::class, 'tambahkategori'])->name('tambahkategori');
    Route::post('/editkategori', [WabiDashboardController::class, 'editkategori'])->name('editkategori');
    // Produk
    Route::get('/produk', [WabiDashboardController::class, 'produk'])->name('produk');
    Route::get('/tambahproduk', [WabiDashboardController::class, 'tambahproduk'])->name('tambahproduk');
    Route::get('/editproduk/{id}', [WabiDashboardController::class, 'editproduk'])->name('editproduk');
    Route::post('/editproduk', [WabiDashboardController::class, 'saveeditproduk'])->name('saveeditproduk');
    Route::post('/saveproduk', [WabiDashboardController::class, 'saveproduk'])->name('saveproduk');
    Route::post('/hapusproduk', [WabiDashboardController::class, 'hapusproduk'])->name('hapusproduk');
    Route::post('/updateproduk', [WabiDashboardController::class, 'updateproduk'])->name('updateproduk');
    // Pengguna
    Route::get('/pengguna', [WabiDashboardController::class, 'pengguna'])->name('pengguna');
    Route::get('/editpengguna', [WabiDashboardController::class, 'editpengguna'])->name('editpengguna');
    Route::post('/savepengguna', [WabiDashboardController::class, 'savepengguna'])->name('savepengguna');
    Route::post('/hapuspengguna', [WabiDashboardController::class, 'hapuspengguna'])->name('hapuspengguna');
});