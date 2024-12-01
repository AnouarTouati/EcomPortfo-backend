<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        if (Auth::user()) {
            return response(json_encode(Auth::user()), 200)->withHeaders([
                'Content-Type' => 'application/json'
            ]);
        }
        $validated = $request->validate([
            'email' => 'string|required',
            'password' => 'string|required|min:6',
        ]);
        //session id changes when we log in so we catch the cart before attempting to login
        $cart = Cart::where('session_id', $request->session()->getId())->first();

        if (Auth::attempt($validated, $request->remeber_me ?? false)) {

            if ($cart) {
                if (Auth::user()->cart) {
                    foreach ($cart->products as $product) {
                        $user_product = Auth::user()->cart->products()->find($product->id);
                        if ($user_product) {

                            $user_product->pivot->quantity += $product->pivot->quantity;
                            $user_product->pivot->save();
                        } else {

                            Auth::user()->cart->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
                        }
                    }
                    $cart->products()->detach();
                    $cart->delete();
                } else {
                    Log::debug('called 7');
                    $cart->session_id = null;
                    $cart->user()->associate(Auth::user());
                    $cart->save();
                }
            }

            return response()->json(Auth::user(), 200);
        } else {
            return response()->json('failed to login', 400);
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
    }
}
