@extends('admin::layouts.master')
@section('pageTitle')
    Countries
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Országok', 'subtitle' => 'Összes', 'button' => route('countries.create')])
@endsection

@section('content')

    @livewire('countries::cards') 

@endsection
