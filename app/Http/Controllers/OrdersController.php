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
        $products = Order::whereHas('user', function ($query) {
            if(request()->has('q')) {
                $keyword = '%'.request()->get('q').'%';
                $builder = $query->where('name', 'like', $keyword);
            }
        });
        if(request()->has('d') && request()->get('d')){
            $arr = [
                'start' => Carbon::parse(substr(request()->get('d'), 4, 11))->startOfDay(),
                'end' =>  Carbon::parse(substr(request()->get('d'), 64, -44))->endOfDay()
            ];
            $products = $products->whereBetween('created_at', [$arr['start'],$arr['end']]);
        }
        $products->count() ? $orders = $products : $errorFound = true;
        return $errorFound === false ? OrdersResource::collection($orders->latest()
                                                                         ->paginate(10)
                                                                         ->appends(request()->query())) 
                                                                         : $error;
    }
    public function show($id){
        $order = Order::findOrFail($id);
        return new OrdersResource($order);
    }
}
