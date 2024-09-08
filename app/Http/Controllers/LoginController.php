<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        if(Auth::user()){
            return response(json_encode(Auth::user(),200))->withHeaders([
                'Content-Type'=>'application/json'
            ]);
        }
       $validated = $request->validate([
        'email'=>'string|required',
        'password'=>'string|required|min:6',
       ]);
       //session id changes when we log in so we catch the cart before attempting to login
       $cart = Cart::where('session_id',$request->session()->getId())->first();

       if(Auth::attempt($validated,$request->remeber_me ?? false)){
        if($cart){
            $cart->user()->associate(Auth::user());
            $cart->save();
        }
        
        return response(json_encode(Auth::user(),200))->withHeaders([
            'Content-Type'=>'application/json'
        ]);
       }
       else {
        return response('failed to login',400);
       }
    }

    public function logout(){
       
        Auth::guard('web')->logout();
        $cart =  Auth::user()->cart;
        if($cart){
            $cart->session_id = Session::getId();
            $cart->save();
        }
        
    }
}
