@extends('admin::layouts.master')
@section('pageTitle')
    Alkategóriák
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Alkategóriák', 'subtitle' => 'Összes', 'button' => route('subcategories.create')])
@endsection

@section('content')

    @livewire('subcategories::listcomponent')
    {{-- @livewire('subcategories::cards') --}}

@endsection
