@extends('admin::layouts.master')
@section('pageTitle')
    Templates
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Sablonok', 'subtitle' => 'Összes'])
@endsection

@section('content')

    {{--@livewire('templates::listcomponent')--}}
     @livewire('templates::cards') 

@endsection
