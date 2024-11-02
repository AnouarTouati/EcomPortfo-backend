<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Rules\OrderByColumnExists;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->hasPermissionTo('view products')) {
                $request->validate([
                    'rowsPerPage' => 'numeric|integer',
                    'order' => 'in:asc,desc',
                    'orderBy' => [new OrderByColumnExists('products')]
                ]);
                $products = Product::orderBy($request->input('orderBy', 'id'), $request->order)->paginate($request->rowsPerPage);

                return response()->json($products);
            }
        }
        return response()->json('', 403);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->hasPermissionTo('add products')) {
                $request->validate([
                    'stripeId' => 'required|string|size:30',
                    'name' => 'required|string|min:3|max:255',
                    'description' => 'required|string|max:255',
                    'price' => 'required|numeric',
                ]);

                $product = new Product();
                $product->stripe_id = $request->stripeId;
                $product->name = $request->name;
                $product->description = $request->description;
                $product->price = $request->price;
                $product->save();
                $product->refresh();

                return response(json_encode($product), 201);
            }
        }
        return response()->json('', 403);
    }

    public function show(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->hasPermissionTo('delete products')) {
                try {
                    $product->delete();
                    return response('', 200);
                } catch (Exception $e) {
                    return response('', 500);
                }
            }
        }
        return response()->json('', 403);
    }
}
