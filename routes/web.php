<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;


Route::get('/', [SaleController::class, 'index'])->name('home');

  Route::resource('sales', SaleController::class);

 Route::get('sales-trash', [SaleController::class, 'trash'])->name('sales.trash');
 Route::post('sales/{id}/restore', [SaleController::class, 'restore'])->name('sales.restore');
 