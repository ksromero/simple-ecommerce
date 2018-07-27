@extends('layouts.main')

@section('content')
    @include('flash::message')
    <h3>Shopping Cart <i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i></h3>
    <hr>
    @if(session()->has('cart'))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-md-2 col-lg-2">COVER IMAGE</th>
                    <th class="col-md-2 col-lg-2">NAME</th>
                    <th class="col-md-2 col-lg-2">QUANTITY</th>
                    <th class="col-md-2 col-lg-2"></th>
                    <th class="col-md-2 col-lg-2">PRICE</th>
                </tr>
            </thead>
            <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td><img src="{{ asset("cover_images/".$product['item']['cover_image']) }}" width="180" height="180" class="img-thumbnail"></td>
                            <td><strong>{{ $product['item']['name'] }}</strong><br> {{$product['item']['description']}}</td>
                            <td>
                                <form method="post" class="form-inline" action="{{ route('updateProduct', ['id' => $product['item']['id']]) }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="input-group">
                                            <span class="input-group-btn"><button class="btn btn-default">Update</button></span>
                                            <input type="text" class="form-control" name="qty" value="{{ $product['qty'] }}">
                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td> 
                                <div class="dropdown">
                                    <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Reduce
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li> <a href="{{ route('removeByOne', ['id' => $product['item']['id']])}}">Reduce By One</a></li>
                                        <li> <a href="{{ route('removeAll', ['id' => $product['item']['id']])}}">Reduce All</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>&#8369; {{ $product['price'] }}</td>
                        </tr>
                    @endforeach           
            </tbody>
            <tfoot>
                <tr>
                    <td class="bg-warning">Subtotal</td>
                    <td class="bg-warning"></td>
                    <td class="bg-warning"></td>
                    <td class="bg-warning"></td>       
                    <td class="bg-warning">&#8369; {{ $totalPrice }}</td>
                </tr>
                <tr>
                    <td class="bg-success">Total</td>
                    <td class="bg-success"></td>
                    <td class="bg-success"></td>
                    <td class="bg-success"></td> 
                    <td class="bg-success">&#8369; {{ $totalPrice }}</td>
                </tr>
            </tfoot>
        </table>
        <hr>
        <a href="#" class="btn btn-primary pull-right" style="margin-bottom:20px;">Checkout</a>
    @else
        <div class="alert alert-warning" role="alert">No products in cart yet!</div>
    @endif
    <a href="{{route('home')}}" class="btn btn-default pull-right" style="margin-bottom:20px;">Continue Shopping</a>
@endsection