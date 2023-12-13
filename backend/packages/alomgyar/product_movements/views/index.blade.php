@extends('admin::layouts.master')
@section('pageTitle')
    Product_movements
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Bizonylatok', 'subtitle' => 'Lista'])
@endsection

@section('content')
    @livewire('product_movements::listcomponent')
    {{-- @livewire('product_movements::cards') --}}
@endsection
