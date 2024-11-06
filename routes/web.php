<?php

use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Sales\SaleController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware('auth')->controller(SaleController::class)->group(function(){
    Route::get('/vender/bodega', 'selectWarehouse')->name('sales.select-warehouse');
    Route::post('/vender/bodega', 'setWarehouse')->name('sales.set-warehouse');
    Route::get('/vender', 'create')->name('sales.create');
    Route::post('/vender', 'store')->name('sales.store');
});

Route::middleware('auth')->controller(PurchaseController::class)->group(function(){
    Route::get('/comprar', 'create')->name('purchases.create');
    Route::post('/comprar', 'store')->name('purchases.store');
});
