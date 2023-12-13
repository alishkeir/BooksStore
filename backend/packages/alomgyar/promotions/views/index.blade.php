@extends('admin::layouts.master')
@section('pageTitle')
    Akciók
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Akciók', 'subtitle' => 'Összes', 'button' => route('promotions.create')])
@endsection

@section('content')

    {{-- @livewire('promotions::listcomponent') --}}
    @livewire('promotions::cards')

@endsection
