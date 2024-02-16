<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(Auth::user()){
            $cart = Cart::where('user_id',Auth::user()->id)->first();
        }else {
            $cart = Cart::where('session_id',$request->session()->getId())->first();
        }
        if($cart == null){
            return response(json_encode([]),200)->withHeaders([
                'Content-type'=>'application/json'
            ]);
        }
        return response(json_encode($cart->products),200)->withHeaders([
            'Content-type'=>'application/json'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'product_id'=>'required|exists:products,id'
        ]);
        if(Auth::user()){
            $cart = Cart::where('user_id',Auth::user()->id)->first();
        }else{
            $cart = Cart::where('session_id',$request->session()->getId())->first();
        }

        if($cart == null){
            $cart = new Cart();
            $cart->session_id=$request->session()->getId();
            if(Auth::user()){
                $cart->user()->save(Auth::user());
            }
            $cart->save();
           
        }
        $cart->products()->attach($validated['product_id']);
        return response('',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
