@extends('admin::layouts.master')
@section('pageTitle')
    Beszállítók
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Beszállítók', 'subtitle' => 'Lista', 'button' => route('suppliers.create')])
@endsection

@section('content')

    @livewire('suppliers::listcomponent')
    {{-- @livewire('suppliers::cards') --}}

@endsection
