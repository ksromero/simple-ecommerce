<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Http\Resources\OrdersResource;

class OrdersController extends Controller
{
    public function index(){
        $errorFound = false;
        $error = ['error' => 'No Results Found'];
        $products = Order::whereHas('user', function ($query) {
            if (request()->has('q')) {
                $keyword = '%'.request()->get('q').'%';
                $builder = $query->where('name', 'like', $keyword);
            }
        });
        $products->with('products');
        $products->count() ? $orders = $products : $errorFound = true;
        return $errorFound === false ? OrdersResource::collection($orders->latest()->paginate(5)) : $error;
    }
    public function show($id){
        $user = Order::findOrFail($id);
        return new OrdersResource($user);
    }
}
