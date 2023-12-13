@extends('admin::layouts.master')
@section('pageTitle')
    Coupons
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Coupons', 'subtitle' => 'All coupons', 'button' => route('coupons.create')])
@endsection

@section('content')

    @livewire('coupons::listcomponent')
    {{-- @livewire('coupons::cards') --}}

@endsection
