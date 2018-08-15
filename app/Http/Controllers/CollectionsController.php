<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Http\Resources\OrderProductResourceCollection;

class CollectionsController extends Controller
{
    public function index(){
        $errorFound = false;
        $error = ['error' => 'No Results Found'];
        $products = Product::with('orders');
        if(request()->has('q')) {
            $keyword = '%'.request()->get('q').'%';
            $builder = $products->where('name', 'like', $keyword);
            $builder->count() ? $products = $builder : $errorFound = true;
        }
        if(request()->has('d') && request()->get('d')){
            $arr = [
                'start' => Carbon::parse(substr(request()->get('d'), 4, 11))->startOfDay(),
                'end' =>  Carbon::parse(substr(request()->get('d'), 64, -44))->endOfDay()
            ];
            $products = $products->whereBetween('created_at', [$arr['start'],$arr['end']]);
        }
        return $errorFound === false ? new OrderProductResourceCollection($products->get()) : $error;
    }
}
