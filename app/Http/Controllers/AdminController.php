<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('admin.dashboard');
    }

    public function products(){
        return view('admin.products');
    }
    public function employees(){
        if(!Gate::allows('isAdmin')){
            abort(404,"Sorry, you can't perform this action");
        }
        return view('admin.employees');
    }
    public function orders(){
        return view('admin.orders');
    }
    public function collections(){
        if(!Gate::allows('isAdmin')){
            abort(404,"Sorry, you can't perform this action");
        }
        return view('admin.collections');
    }
}
