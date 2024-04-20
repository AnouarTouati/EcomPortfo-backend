<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Rules\OrderBy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
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
