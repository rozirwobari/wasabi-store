<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WabiApiController;

Route::post('/midtrans/notification', [WabiApiController::class, 'MidtransCallback'])->name('midtrans.callback');
Route::post('/game/updatepesanan', [WabiApiController::class, 'updatepesanan'])->name('game.updatepesanan');
Route::post('/game/linked', [WabiApiController::class, 'linkedRespon'])->name('game.linkedRespon');
