<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Rules\OrderByColumnExists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->hasPermissionTo('view orders')) {
                $request->validate([
                    'rowsPerPage' => 'numeric|integer',
                    'order' => 'in:asc,desc',
                    'orderBy' => [new OrderByColumnExists('orders')]
                ]);
                $orders = Order::orderBy($request->input('orderBy', 'id'), $request->order)->paginate($request->rowsPerPage);
                return response()->json($orders);
            }
        }
        return response()->json('', 403);
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
        $user = Auth::user();
        if ($user) {
            if ($user->hasPermissionTo('view orders')) {
                $order->load('products');

                return response()->json($order);
            }
        }
        return response()->json('', 403);
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
