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
        flash('Item Added to Cart')->success();
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
    public function reduceByOne($id){
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        //dd($cart->items);
        $cart->reduceByOne($id);
        if(count($cart->items) > 0){
            session()->put('cart', $cart);
        }else{
            session()->forget('cart');
        }
        flash('Item Reduced Successfully')->success();
        return redirect()->route('getCart');
    }
    public function removeProduct($id){
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->reduceAll($id);
        //dd($cart->items);
        if(count($cart->items) > 0){
            session()->put('cart', $cart);
        }else{
            session()->forget('cart');
        }
        flash('Item Removed Successfully')->success();
        return redirect()->route('getCart');
    }
    public function updateProduct($id, Request $request){
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->updateCart($id, $request->input('qty'));
        //dd($cart->items);
        if(count($cart->items) > 0){
            session()->put('cart', $cart);
        }else{
            session()->forget('cart');
        }
        flash('Item Updated Successfully')->success();
        return redirect()->route('getCart');
    }
}
