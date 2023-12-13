@extends('admin::layouts.master')
@section('pageTitle')
    Ügyfelek
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Ügyfelek', 'subtitle' => 'Összes', 'button' => route('customers.create')])
@endsection

@section('content')

    @livewire('customers::listcomponent')
    {{-- @livewire('customers::cards') --}}

@endsection
