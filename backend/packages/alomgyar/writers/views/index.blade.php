@extends('admin::layouts.master')
@section('pageTitle')
    Írók
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Írók', 'subtitle' => 'Összes író', 'button' => route('writers.create')])
@endsection

@section('content')

    @livewire('writers::listcomponent')
    {{-- @livewire('publishers::cards') --}}

@endsection
