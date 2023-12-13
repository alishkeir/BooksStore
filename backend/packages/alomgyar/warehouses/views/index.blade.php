@extends('admin::layouts.master')
@section('pageTitle')
    Raktárak
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Raktárak', 'subtitle' => 'Összes', 'button' => route('warehouses.create')])
@endsection

@section('content')

    @livewire('warehouses::listcomponent')
    {{-- @livewire('warehouses::cards') --}}

@endsection
