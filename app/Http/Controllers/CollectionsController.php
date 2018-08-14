<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Carbon\Carbon;
use App\Http\Resources\OrderProductResourceCollection;

class CollectionsController extends Controller
{
    public function index(){
        $errorFound = false;
        $error = ['error' => 'No Results Found'];
        $products = Product::with('orders');
        if (request()->has('q')) {
            $keyword = '%'.request()->get('q').'%';
            $builder = $products->where('name', 'like', $keyword);
            $builder->count() ? $products = $builder : $errorFound = true;
        }
        return $errorFound === false ? new OrderProductResourceCollection($products->latest()->paginate()) : $error;
    }
}
