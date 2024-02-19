<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

enum  Status: int
{
    case unpaid = 0;
    case paid = 1;
}

class OrderController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'email|max:255|required'
        ]);

        $order = new Order();
        $order->email = $validated['email'];
        $order->status = Status::unpaid;
        $order->total = 0;
        $order->save();
        $cart = CartController::getCurrentCart();
        if ($cart == null)
            return response('cart empty', 200);

        $sum = 0;
        foreach ($cart->products as $product) {
            $price_after_discount = $product->price;
            $total = $price_after_discount * $product->pivot->quantity;
            $sum += $total;
            $order->products()->save($product, [
                'quantity' => $product->pivot->quantity,
                'price_at_selling_time' => $product->price,
                'price_after_discount' =>  $price_after_discount,
                'coupon_code_used' => null,
                'total'=>  $total
            ]);
        }
        $order->total = $sum;
        $order->save();
        $cart->delete();
        return response('ok', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
