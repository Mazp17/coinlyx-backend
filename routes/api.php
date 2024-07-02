<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth.bearer')->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class)->only(['index', 'show', 'store']);
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class)->only(['index', 'create']);
    Route::get('/transactions/account/{accountNumber}', [\App\Http\Controllers\TransactionController::class, 'getByAccountNumber']);
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
});

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::get('/healthcheck', function () {
    return response()->json(['status' => 'ok']);
});
