@extends('admin::layouts.master')
@section('pageTitle')
    Kiadók
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Kiadók', 'subtitle' => 'Összes kiadó', 'button' => route('publishers.create')])
@endsection

@section('content')

    @livewire('publishers::listcomponent')
    {{-- @livewire('publishers::cards') --}}

@endsection
