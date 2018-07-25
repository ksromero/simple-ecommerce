<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return view('products.index');
    }
    public function cart(){
        return view('cart.index');
    }
}
