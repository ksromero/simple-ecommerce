<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function products()
    {
        return view('admin.products');
    }
    public function employees()
    {
        return view('admin.employees');
    }
}
