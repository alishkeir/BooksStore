@extends('admin::layouts.master')
@section('pageTitle')
    Szerzők
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Szerzők', 'subtitle' => 'Összes', 'button' => route('authors.create')])
@endsection

@section('content')

    @livewire('authors::listcomponent')
    {{-- @livewire('authors::cards') --}}

@endsection
