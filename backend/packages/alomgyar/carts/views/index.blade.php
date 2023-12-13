@extends('admin::layouts.master')
@section('pageTitle')
    Carts
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Carts', 'subtitle' => 'All carts', 'button' => route('carts.create')])
@endsection

@section('content')

    @livewire('carts::listcomponent')
    {{-- @livewire('carts::cards') --}}

@endsection
