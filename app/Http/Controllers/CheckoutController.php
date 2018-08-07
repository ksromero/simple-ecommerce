<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Order;
use App\OrderProduct;
use App\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\ExpressCheckout;

class CheckoutController extends Controller
{
    protected $provider;
    public function __construct(){
        $this->provider = new ExpressCheckout;
    }
    public function index(){
        if(!session()->has('cart')){
            return view('cart.index', ['products' => null]);
        }
        $oldCart = session()->get('cart');
        $cart = new Cart($oldCart);
        //dd($cart->items);
        return view('checkout.index',['products' => $cart->items, 'totalPrice' => $cart->totalPrice]);
    }
    public function getExpressCheckout(Request $request){
        $response = $this->provider->setExpressCheckout($this->productData());
        return redirect($response['paypal_link']);
    }
    public function paypalSuccess(Request $request){
        $token = $request->get('token');
        $payerID = $request->get('PayerID');
        $data = $this->productData();
        $response = $this->provider->doExpressCheckoutPayment($data,$token,$payerID);
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $order = Order::create([ 
            'user_id' => Auth::id(), 
            'address' => 'Test Address',
        ]); 
        
        foreach($cart->items as $row){ 
            $items[$row['item']->id] = ['quantity' => $row['qty']]; 
        }
        $order->products()->attach($items);
        session()->forget('cart');
        unset($cart);
        flash('Item Ordered Successfully')->success();
        return redirect()->route('getCart');
    }
    public function productData(){
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $data = [];
        $data['items'] = [];
        foreach($cart->items as $product){
            $shop = [
                'name' => $product['item']['name'],
                'price' => $product['item']['price'],
                'qty' => $product['qty'],
            ];
            $data['items'][] = $shop;
        }
        $data['invoice_id'] = uniqid();
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('checkoutSuccess');
        $data['cancel_url'] = url('/cart');
        $data['total'] = $cart->totalPrice;
        //give a discount of 10% of the order amount
        $data['shipping_discount'] = round((10 / 100) * $cart->totalPrice, 2);
        return $data;
    }
}
