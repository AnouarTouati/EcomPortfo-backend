<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public static function getCurrentCart()
    {
        $cart = null;
        if (Auth::user()) {
            $cart = Cart::where('user_id', Auth::user()->id)->first();
        } else {
            $cart = Cart::where('session_id', Session::getId())->first();
        }
        return $cart;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()) {
            $cart = Cart::where('user_id', Auth::user()->id)->first();
        } else {
            $cart = Cart::where('session_id', $request->session()->getId())->first();
        }
        if ($cart == null) {
            return response(json_encode([]), 200)->withHeaders([
                'Content-type' => 'application/json'
            ]);
        }
        return response(json_encode($cart->products), 200)->withHeaders([
            'Content-type' => 'application/json'
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        if (Auth::user()) {
            $cart = Cart::where('user_id', Auth::user()->id)->first();
        } else {
            $cart = Cart::where('session_id', $request->session()->getId())->first();
        }

        if ($cart == null) {
            $cart = new Cart();
            $cart->session_id = $request->session()->getId();
            if (Auth::user()) {
                $cart->user()->save(Auth::user());
            }
            $cart->save();
        }
        if ($cart->products()->find($validated['product_id'])) {
            $product = $cart->products()->find($validated['product_id']);
            $product->pivot->quantity += 1;
            $product->pivot->save();
        } else {
            $cart->products()->attach($validated['product_id'], ['quantity' => '1']);
        }
        return response('', 201);
    }

    public function increase($product_id)
    {
        $product_in_cart = $this->getProductFromCurrentCart($product_id);
        $product_in_cart->pivot->quantity++;
        $product_in_cart->pivot->save();
        return response('ok', 200);
    }
    public function decrease($product_id)
    {
        $product_in_cart = $this->getProductFromCurrentCart($product_id);
        if ($product_in_cart->pivot->quantity > 1) {
            $product_in_cart->pivot->quantity--;
            $product_in_cart->pivot->save();
        }
        return response('ok', 200);
    }
    private function getProductFromCurrentCart($product_id)
    {
        $cart = CartController::getCurrentCart();
        if ($cart == null)
            return response('cart empty', 200);
        $product_in_cart = $cart->products()->find($product_id);
        if (!$product_in_cart) {
            return response('product not in cart');
        }
        return $product_in_cart;
    }

    public function productsCount()
    {
        $cart = CartController::getCurrentCart();
        if ($cart) {
            $count = $cart->products()->count();
        } else {
            $count = 0;
        }
        return response(json_encode(['count' => $count]), 200)->withHeaders([
            'Content-type' => 'application/json'
        ]);
    }
    public function removeProduct($product_id){
        $cart = CartController::getCurrentCart();
        if($cart){
            $cart->products()->detach($product_id);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
