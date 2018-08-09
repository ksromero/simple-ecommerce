<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Http\Requests;
use Image;
use App\Http\Resources\ProductsResource;

class ProductsController extends Controller
{
    public function index()
    {
        $errorFound = false;
        $error = ['error' => 'No Results Found'];
        $products = Product::with('orders')->latest();
        if (request()->has('q')) {
            $keyword = '%'.request()->get('q').'%';
            $builder = $products;
            $builder = $builder->where('name', 'like', $keyword);
            $builder->count() ? $products = $builder : $errorFound = true;
        }
       return $errorFound === false ? ProductsResource::collection($products->paginate(6)) : $error;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'cover_image' => 'image|required|image64:jpeg,jpg,png|max:2048'
        ]);
        $image = $request->input('cover_image');
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        Image::make($request->get('cover_image'))->save(public_path('cover_images/').$name);
        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->cover_image = $name;
        if($product->save()) {
            return new ProductsResource($product);
        }
    }
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'cover_image' => 'image|required|image64:jpeg,jpg,png|max:2048'
        ]);
       $product = Product::findOrFail($id);
       $image = $request->input('cover_image');
       $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
       Image::make($request->get('cover_image'))->save(public_path('cover_images/').$name);
       $product->name = $request->input('name');
       $product->description = $request->input('description');
       $product->price = $request->input('price');
       $product->cover_image = $name;
       if($product->save()) 
       {
           return new ProductsResource($product);
       }
    }
    public function show($id)
    {
        $product = Product::with('orders')->findOrFail($id);
        return new ProductsResource($product);
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return new ProductsResource($product);
    }
}
