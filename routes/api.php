<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
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



Route::middleware('auth:sanctum')->group(function(){

    Route::get('/session',function(Request $request){
        return response(json_encode($request->session()->getId()),200)->withHeaders([
            'Content-type'=>'application/json'
        ]);
    });
    Route::post('/cart/products',[CartController::class,'store']);
    Route::get('/cart/products',[CartController::class,'index']);
    
});
