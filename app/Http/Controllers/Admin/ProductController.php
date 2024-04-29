<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Rules\OrderBy;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $request->validate([
            'rowsPerPage'=>'numeric|integer',
            'order'=>'in:asc,desc',
            'orderBy'=>[new OrderBy('products')]
        ]);
        $products = Product::orderBy($request->input('orderBy','id'),$request->order)->paginate($request->rowsPerPage);
        
        return response(json_encode($products),200)->withHeaders([
            'Content-type'=>'application/json'
        ]);
    }

    public function store(Request $request)
    {
         $request->validate([
            'stripeId'=>'required|string|size:30',
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'price'=>'required|numeric',
        ]);

        $product = new Product();
        $product->stripe_id = $request->stripeId;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();
        $product->refresh();

        return response(json_encode($product),201);
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
        try{
            $product->delete();
            return response('',200);
        }catch(Exception $e){
            return response('',500);
        }
    }
}
