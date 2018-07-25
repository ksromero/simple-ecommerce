<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;
use App\Http\Requests;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::findOrFail(request()->input('id'));
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        request()->input('qty') ? $cart->add($product, $product->id, request()->input('qty')) : $cart->add($product, $product->id);
        $request->session()->put('cart', $cart);
        //dd($request->session()->get('cart'));
        return redirect()->route('getCart');
    }
    public function getCart(){
        if(!session()->has('cart')){
            return view('cart.index', ['products' => null]);
        }
        $oldCart = session()->get('cart');
        $cart = new Cart($oldCart);
        //dd($cart->items);
        return view('cart.index',['products' => $cart->items, 'totalPrice' => $cart->totalPrice]);
    }
}
