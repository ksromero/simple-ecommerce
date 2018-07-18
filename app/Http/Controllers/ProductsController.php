<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Http\Requests;
use App\Http\Resources\ProductsResource;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at','desc')->paginate(5);
        return ProductsResource::collection($products);
    }
    public function store(Request $request)
    {
        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        if($product->save()) 
        {
            return new ProductsResource($product);
        }
    }
    public function update($id, Request $request)
    {
       $product = Product::findOrFail($id);
       $product->name = $request->input('name');
       $product->description = $request->input('description');
       $product->price = $request->input('price');
       if($product->save()) 
       {
           return new ProductsResource($product);
       }
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductsResource($product);
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return new ProductsResource($product);
    }
}
