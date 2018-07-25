@extends('layouts.main')

@section('content')
    <h3>Shopping Cart <i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i></h3>
    <hr>
    @if(session()->has('cart'))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-md-2 col-lg-2">COVER IMAGE</th>
                    <th class="col-md-2 col-lg-2">NAME</th>
                    <th class="col-md-2 col-lg-2">QUANTITY</th>
                    <th class="col-md-2 col-lg-2">PRICE</th>
                </tr>
            </thead>
            <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td><img src="{{ asset("cover_images/".$product['item']['cover_image']) }}" width="180" height="180" class="img-thumbnail"></td>
                            <td><strong>{{ $product['item']['name'] }}</strong><br> {{$product['item']['description']}}</td>
                            <td>{{ $product['qty'] }}</td>
                            <td>&#8369; {{ $product['price'] }}</td>
                        </tr>
                    @endforeach           
            </tbody>
            <tfoot>
                <tr>
                    <td class="bg-warning">Subtotal</td>
                    <td class="bg-warning"></td>
                    <td class="bg-warning"></td>    
                    <td class="bg-warning">&#8369; {{ $totalPrice }}</td>
                </tr>
                <tr>
                    <td class="bg-success">Total</td>
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
@endsection