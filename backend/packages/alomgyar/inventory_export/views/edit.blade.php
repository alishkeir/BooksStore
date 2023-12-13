@extends('admin::layouts.master')
@section('pageTitle')
Leltár
@endsection

@section('js')
    @include('inventory_export::_formjs')
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Leltár', 'subtitle' => 'Szerkesztés'])
@endsection

@section('content')

    @include('inventory_export::_form')

@endsection
