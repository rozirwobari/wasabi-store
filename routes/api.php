<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WabiApiController;

Route::post('/midtrans/notification', [WabiApiController::class, 'MidtransCallback'])->name('midtrans.callback');
Route::post('/game/webhook', [WabiApiController::class, 'GameWebhook'])->name('game.webhook');