@extends('admin::layouts.master')
@section('pageTitle')
    Könyvesboltok
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Könyvesboltok', 'subtitle' => 'Összes', 'button' => route('shops.create')])
@endsection

@section('content')

    @livewire('shops::listcomponent')
    {{-- @livewire('shops::cards') --}}

@endsection
