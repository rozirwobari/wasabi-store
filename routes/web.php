<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WabiStoreController;
use App\Http\Controllers\WabiDashboardController;

Auth::routes();

Route::get('/', [WabiStoreController::class, 'index']);
Route::get('/produk-details/{id}', [WabiStoreController::class, 'produkdetail'])->name('produk-details');
Route::get('/cart', [WabiStoreController::class, 'cart'])->middleware('auth');
Route::get('/dashboard', [WabiStoreController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/profile', [WabiStoreController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/orders', [WabiStoreController::class, 'orders'])->name('orders')->middleware('auth');
Route::get('/settings', [WabiStoreController::class, 'settings'])->name('settings')->middleware('auth');
Route::get('/order-details/{invoice}', [WabiStoreController::class, 'orderdetails'])->name('order-details')->middleware('auth');
Route::post('/addtocarts', [WabiStoreController::class, 'addtocart'])->name('addtocarts')->middleware('auth');
Route::post('/deletecarts', [WabiStoreController::class, 'deletecarts'])->name('deletecarts')->middleware('auth');
Route::post('/updatecarts', [WabiStoreController::class, 'updatecarts'])->name('updatecarts')->middleware('auth');
Route::get('/checkout', [WabiStoreController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('/invoice/{invoice}', [WabiStoreController::class, 'invoice'])->name('invoice')->middleware('auth');
Route::post('/update-orders', [WabiStoreController::class, 'updateorders'])->name('update-orders')->middleware('auth');
Route::post('/profileupdate', [WabiStoreController::class, 'profileupdate'])->name('profileupdate')->middleware('auth');
Route::post('/changepassword', [WabiStoreController::class, 'changepassword'])->name('changepassword')->middleware('auth');

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