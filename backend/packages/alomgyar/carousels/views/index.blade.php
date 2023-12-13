@extends('admin::layouts.master')
@section('pageTitle')
    Carousels
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Carousels', 'subtitle' => 'All carousels', 'button' => route('carousels.create')])
@endsection

@section('content')

     @livewire('carousels::cards')

@endsection
