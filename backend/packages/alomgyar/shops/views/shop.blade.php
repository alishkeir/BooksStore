@extends('admin::layouts.master')
@section('pageTitle')
    Könyvesboltok
@endsection

@section('header')
    @include('shops::layouts.header', ['title' => 'Rendelésfelvétel', 'subtitle' => ''])
@endsection

@section('content')

    @livewire('shops::shopcomponent')

@endsection
