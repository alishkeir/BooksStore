@extends('admin::layouts.master')
@section('pageTitle')
Készletszám
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Készletszám', 'subtitle' => ''])
@endsection

@section('content')
    @livewire('inventory_export::inventory-count')
@endsection