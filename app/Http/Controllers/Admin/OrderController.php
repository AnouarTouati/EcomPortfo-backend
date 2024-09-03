<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Rules\OrderByColumnExists;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'rowsPerPage'=>'numeric|integer',
            'order'=>'in:asc,desc',
            'orderBy'=>[new OrderByColumnExists('orders')]
        ]);
        $orders = Order::orderBy($request->input('orderBy','id'),$request->order)->paginate($request->rowsPerPage);
        return response(json_encode($orders),200)->withHeaders([
            'Content-Type'=>'application/json'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('products');
    
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
