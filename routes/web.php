<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WabiDashboardAdmin;

use App\Http\Controllers\Admin\WabiAdminDashboard;
use App\Http\Controllers\Admin\WabiAdminKategori;
use App\Http\Controllers\Admin\WabiAdminProduk;
use App\Http\Controllers\Admin\WabiAdminUsers;
use App\Http\Controllers\Admin\WabiAdminOrders;

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

// Route untuk aktivasi akun
Route::get('/activate/{token}', [RegisterController::class, 'activate'])->name('account.activate');
Route::post('/resend-activation', [RegisterController::class, 'resendActivation'])->name('account.resend-activation');

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
    Route::post('/resendlinked', [WabiDashboardUser::class, 'resendlinked'])->name('resendlinked');
});

Route::get('/adminlogin', [WabiAdminAuth::class, 'admin'])->name('admin.login');
Route::post('/authadmin', [WabiAdminAuth::class, 'authadmin'])->name('authadmin');
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/', [WabiAdminDashboard::class, 'index'])->name('admin.home');
    // Kategori
    Route::get('/kategori', [WabiAdminKategori::class, 'index'])->name('kategori');
    Route::post('/hapuskategori', [WabiAdminKategori::class, 'hapuskategori'])->name('hapuskategori');
    Route::post('/tambahkategori', [WabiAdminKategori::class, 'tambahkategori'])->name('tambahkategori');
    Route::post('/editkategori', [WabiAdminKategori::class, 'editkategori'])->name('editkategori');
    // Produk
    Route::get('/produk', [WabiAdminProduk::class, 'produk'])->name('produk');
    Route::get('/tambahproduk', [WabiAdminProduk::class, 'tambahproduk'])->name('tambahproduk');
    Route::get('/editproduk/{id}', [WabiAdminProduk::class, 'editproduk'])->name('editproduk');
    Route::post('/saveproduk', [WabiAdminProduk::class, 'saveproduk'])->name('saveproduk');
    Route::post('/hapusproduk', [WabiAdminProduk::class, 'hapusproduk'])->name('hapusproduk');
    Route::post('/updateproduk', [WabiAdminProduk::class, 'updateproduk'])->name('updateproduk');
    // Orders
    Route::get('/orders', [WabiAdminOrders::class, 'index'])->name('orders');
    Route::get('/show-orders/{invoice}', [WabiAdminOrders::class, 'show'])->name('show.orders');
    // Pengguna
    Route::get('/pengguna', [WabiAdminUsers::class, 'index'])->name('pengguna');
    Route::get('/editpengguna/{id}', [WabiAdminUsers::class, 'editpengguna'])->name('editpengguna');
    Route::get('/tambahpengguna', [WabiAdminUsers::class, 'tambahpengguna'])->name('tambahpengguna');
    Route::get('/setting', [WabiAdminUsers::class, 'setting'])->name('setting');
    Route::post('/hapususer', [WabiAdminUsers::class, 'hapususer'])->name('hapususer');
    Route::post('/updatepengguna', [WabiAdminUsers::class, 'updatepengguna'])->name('updatepengguna');
});
