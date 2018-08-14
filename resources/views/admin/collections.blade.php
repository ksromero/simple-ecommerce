@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Collections</h1>
@stop

@section('content')
    <order-products></order-products>
@stop

@section('css')
    
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop