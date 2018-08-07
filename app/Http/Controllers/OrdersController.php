<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Carbon\Carbon;
use App\Http\Resources\OrdersResource;

class OrdersController extends Controller
{
    public function index(){
        $errorFound = false;
        $error = ['error' => 'No Results Found'];
        $products = Order::with('user','products');
        return $errorFound === false ? OrdersResource::collection($products->paginate(5)) : $error;
    }
    public function show($id){
        $user = Order::with('user','product')->findOrFail($id);
        return new OrdersResource($user);
    }
}
