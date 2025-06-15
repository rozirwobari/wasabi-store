<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WabiMidtransController;

Route::post('/midtrans/notification', [WabiMidtransController::class, 'callback'])->name('midtrans.callback');
