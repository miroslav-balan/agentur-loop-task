<?php

use App\Http\Controllers\Api\OrderItemsController;
use App\Http\Controllers\Api\OrderPaymentController;
use App\Http\Controllers\Api\OrdersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::resource('orders', OrdersController::class)
    ->except(['update', 'edit', 'store']);

Route::post('orders/{order}/add', [OrderItemsController::class, 'store'])
    ->name('orders.add');

Route::post('orders/{order}/pay', OrderPaymentController::class)
    ->name('orders.pay');
