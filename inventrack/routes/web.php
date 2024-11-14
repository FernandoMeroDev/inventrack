<?php

use App\Http\Controllers\CashClosing\ShowController as CashClosingShowController;
use App\Http\Controllers\Inventory\EditController as InventoryEditController;
use App\Http\Controllers\Inventory\IndexController as InventoryIndexController;
use App\Http\Controllers\Inventory\AuditController as InventoryAuditController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Purchases\CreateController as PurchaseCreateController;
use App\Http\Controllers\Sales\CreateController as SaleCreateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Receipts\ReceiptController;

Route::get('/', fn() => redirect('login'));

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware(['auth'])->controller(ProductController::class)->group(function(){
    Route::get('/productos', 'index')->name('products.index');
    Route::get('/productos/crear', 'create')->name('products.create');
    Route::post('/productos', 'store')->name('products.store');
    Route::get('/productos/{product}', 'show')->name('products.show');
    Route::get('/productos/{product}/editar', 'edit')->name('products.edit');
    Route::put('/productos/{product}', 'update')->name('products.update');
    Route::delete('/productos/{product}', 'destroy')->name('products.destroy');
});

Route::middleware('auth')->controller(SaleCreateController::class)->group(function(){
    Route::get('/vender/bodega', 'selectWarehouse')->name('sales.select-warehouse');
    Route::post('/vender/bodega', 'setWarehouse')->name('sales.set-warehouse');
    Route::get('/vender', 'create')->name('sales.create');
    Route::post('/vender', 'store')->name('sales.store');
});

Route::middleware('auth')->controller(PurchaseCreateController::class)->group(function(){
    Route::get('/comprar', 'create')->name('purchases.create');
    Route::post('/comprar', 'store')->name('purchases.store');
});

Route::middleware('auth')->controller(CashClosingShowController::class)->group(function(){
    Route::get('/cierre-de-caja/consultar', 'ask')->name('cash-closing.ask');
    Route::get('/cierre-de-caja', 'show')->name('cash-closing.show');
});

Route::middleware('auth')->controller(ReceiptController::class)->group(function(){
    Route::get('/comprobantes/consultar', 'ask')->name('receipts.ask');
    Route::get('/comprobantes', 'index')->name('receipts.index');
    Route::get('/comprobantes/{receipt}', 'show')->name('receipts.show');
    Route::get('/comprobantes/{receipt}/editar', 'edit')->name('receipts.edit');
    Route::put('/comprobantes/{receipt}', 'update')->name('receipts.update');
});

Route::middleware('auth')->controller(InventoryIndexController::class)->group(function(){
    Route::get('/inventario/consultar', 'ask')->name('inventory.ask');
    Route::get('/inventario', 'index')->name('inventory.index');
});

Route::middleware('auth')->controller(InventoryEditController::class)->group(function(){
    Route::get('/inventario/editar', 'edit')->name('inventory.edit');
    Route::get('/inventario/editar/productos', 'editProducts')->name('inventory.edit-products');
    Route::put('/inventario/{level}/productos', 'updateProducts')->name('inventory.update-products');
});

Route::middleware('auth')->controller(InventoryAuditController::class)->group(function(){
    Route::get('/inventario/{warehouse}/auditar', 'askAudit')->name('inventory.ask-audit');
    Route::post('/inventario/{warehouse}/auditar', 'audit')->name('inventory.audit');
});
