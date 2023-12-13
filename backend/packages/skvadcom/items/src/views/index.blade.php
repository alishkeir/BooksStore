@extends('admin::layouts.master')
@section('pageTitle')
    Items
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Items', 'subtitle' => 'All items', 'button' => route('items.create')])
@endsection

@section('content')

    @livewire('items::listcomponent')
    {{-- @livewire('items::cards') --}}

@endsection
