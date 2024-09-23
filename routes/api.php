<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

/**
 * Public Routes
 */

Route::post('login', [LoginController::class, 'login']);
Route::get('/user', [LoginController::class, 'login']);
Route::post('/sign-up', [SignUpController::class, 'signUp']);
Route::get('/products', [ProductController::class, 'index']);

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::group(['prefix' => 'cart'], function () {
    Route::post('products', [CartController::class, 'store']);
    Route::get('products/count', [CartController::class, 'productsCount']);
    Route::delete('products/{product_id}', [CartController::class, 'removeProduct']);
    Route::post('products/{product_id}/quantity/increase', [CartController::class, 'increase']);
    Route::post('products/{product_id}/quantity/decrease', [CartController::class, 'decrease']);
    Route::get('products', [CartController::class, 'index']);
});

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{stripe_session_id}', [OrderController::class, 'show']);


Route::post('/webhook', function (Request $request) {
    Log::debug('payment succeded');
    Log::debug($request->data);
    return response('', 200);
});

/**
 * Member Routes
 */
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout']);
});

/**
 * Admin Routes
 */
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
});
