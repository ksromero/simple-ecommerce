@extends('layouts.main')

@section('content')
    @include('flash::message')
    <h3>Shopping Cart <i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i></h3>
    <hr>


    @if(session()->has('cart'))
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-2 col-lg-2">Cover Image</th>
                <th class="col-md-2 col-lg-2">Name</th>
                <th class="col-md-2 col-lg-2">Quantity</th>
                <th class="col-md-2 col-lg-2"></th>
                <th class="col-md-2 col-lg-2">Price</th>
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
                                    <li> <a href="{{ route('removeAll', ['id' => $product['item']['id']])}}">Remove Product</a></li>
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
    <h3>Payment <i class="fa fa-credit-card"></i> </h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-md-2 col-lg-2">Name</th>
                    <th class="col-md-2 col-lg-2">Description</th>
                    <th class="col-md-2 col-lg-2">Choose Payment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Paypal</td>
                    <td>Paypal | Secured and Easy to Pay Online</td>
                    <td><a href="{{route('checkout')}}" class="btn btn-success" style="margin-bottom:20px;">Pay with Paypal <i class="fa fa-paypal"></i></a></td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="alert alert-warning" role="alert">No products in cart yet!</div>
    @endif
@endsection