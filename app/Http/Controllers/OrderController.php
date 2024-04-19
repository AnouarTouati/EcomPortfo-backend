<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Auth;
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
                'total' =>  $total
            ]);
        }
        $order->total = $sum;
        $order->save();
        $cart->delete();
        return $this->checkout($order);
    }

    private function checkout($order)
    {

        $stripePriceId = 'price_1OoSbFCXHUVQhaJLgyiVmcVL';

        $quantity = 1;
        Auth::attempt(['email' => 'test@example.com', 'password' => 'password']);
        $stripe_session = Auth::user()->checkout([$stripePriceId => $quantity], [
            'success_url' => "http://localhost:5173/payment/success?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => "http://localhost:5173/payment/failed",
        ]);
        $order->stripe_session_id = $stripe_session->id;
        $order->save();

        return response(json_encode(["url" => $stripe_session->url]), 200)->withHeaders(['Content-Type' => 'application/json']);
    }
    /**
     * Display the specified resource.
     */
    public function show($stripe_session_id)
    {
        $order = Order::where("stripe_session_id", $stripe_session_id)->first();

        if ($order == null) {
            return response('not found', 404)->withHeaders(['Content-Type'=>'application/json']);
        }
        if ($order->status == Status::paid) {
            return response('not found', 404)->withHeaders(['Content-Type'=>'application/json']);
        }

        $order->status = Status::paid;
        $order->save();
        return response(json_encode($order), 200)->withHeaders(['Content-Type' => 'application/json']);
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
