@extends('admin::layouts.master')
@section('pageTitle')
    Hozzászólások
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Hozzászólások', 'subtitle' => 'Összes'])
@endsection

@section('content')

    @livewire('comments::listcomponent')
    {{-- @livewire('comments::cards') --}}

@endsection
