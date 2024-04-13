<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('welcome');
});
Route::get('login',function(Request $request){
    if(Auth::attempt(['email'=>'test@example.com','password'=>'password'])){
        return "Logged in";
    }else {
        return "Failed";
    }
});
Route::get('/checkout', function (Request $request) {
    $stripePriceId = 'price_1OoSbFCXHUVQhaJLgyiVmcVL';
 
    $quantity = 1;
    Auth::attempt(['email'=>'test@example.com','password'=>'password']);
   
    return Auth::user()->checkout([$stripePriceId => $quantity], [
        'success_url' => route('success'),
        'cancel_url' => route('cancel'),
    ]);
})->name('checkout');

Route::get('/success',function(Request $request){
    return '<h1>Success</h1>';
})->name('success');

Route::get('/cancel',function(Request $request){
    return '<h1>Cancel</h1>';
})->name('cancel');
