@extends('admin::layouts.master')
@section('pageTitle')
    Oldalak
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Oldalak', 'subtitle' => 'Összes', 'button' => route('pages.create')])
@endsection

@section('content')

    @livewire('pages::listcomponent')
    {{-- @livewire('pages::cards') --}}

@endsection
