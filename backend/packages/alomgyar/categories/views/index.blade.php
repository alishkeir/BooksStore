@extends('admin::layouts.master')
@section('pageTitle')
    Kategóriák
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Kategóriák', 'subtitle' => 'Összes', 'button' => route('categories.create')])
@endsection

@section('content')

    {{-- @livewire('categories::listcomponent')--}}
    @livewire('categories::cards') 

@endsection
