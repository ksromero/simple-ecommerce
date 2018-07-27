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
        $errorFound = false;
        $error = ['error' => 'No Results Found'];
        $products = Product::orderBy('created_at', 'desc');
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
        ]);
        
        $exploded = explode(',', $request->cover_image);
        $decoded = base64_decode($exploded[1]);
        if(str_contains($exploded[0],'jpeg'))
            $extension = 'jpg';
        else
            $extension = 'png';
        $fileName = str_random().'.'.$extension;
        $path = public_path().'/cover_images/'.$fileName;
        file_put_contents($path, $decoded);

        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->cover_image = $fileName;
        if($product->save()) 
        {
            return new ProductsResource($product);
        }
    }
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);
       $product = Product::findOrFail($id);
       $exploded = explode(',', $request->cover_image);
        $decoded = base64_decode($exploded[1]);
        if(str_contains($exploded[0],'jpeg'))
            $extension = 'jpg';
        else
            $extension = 'png';
        $fileName = str_random().'.'.$extension;
        $path = public_path().'/cover_images/'.$fileName;
        file_put_contents($path, $decoded);
       $product->name = $request->input('name');
       $product->description = $request->input('description');
       $product->price = $request->input('price');
       $product->cover_image = $fileName;
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
