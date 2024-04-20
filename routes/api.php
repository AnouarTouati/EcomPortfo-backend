<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login',[LoginController::class,'login']);
Route::middleware('auth:sanctum')->get('/test',function (Request $request){
    return 'something';
});

Route::get('/products',[ProductController::class,'index']);
Route::delete('/products/{product}',[ProductController::class,'destroy']);


Route::post('/cart/products',[CartController::class,'store']);
Route::get('/cart/products/count',[CartController::class,'productsCount']);
Route::delete('/cart/products/{product_id}',[CartController::class,'removeProduct']);
Route::post('/cart/products/{product_id}/quantity/increase',[CartController::class,'increase']);
Route::post('/cart/products/{product_id}/quantity/decrease',[CartController::class,'decrease']);
Route::get('/cart/products',[CartController::class,'index']);

Route::post('/orders',[OrderController::class,'store']);
Route::get('/orders/{stripe_session_id}',[OrderController::class,'show']);

Route::post('/webhook',function(Request $request){
    Log::debug('payment succeded');
    Log::debug(json_decode($request->data));
});
Route::middleware('auth:sanctum')->group(function(){

    Route::get('/session',function(Request $request){
        return response(json_encode($request->session()->getId()),200)->withHeaders([
            'Content-type'=>'application/json'
        ]);
    });
    Route::post('/logout',[LoginController::class,'logout']);
});
