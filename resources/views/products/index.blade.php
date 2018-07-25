@extends('layouts.main')

@section('header')
    @include('header.carousel')
@endsection

@section('content')
    <display-products route="{{ route('getCart') }}" cart="{{ route('postCart') }}"></display-products>
@endsection