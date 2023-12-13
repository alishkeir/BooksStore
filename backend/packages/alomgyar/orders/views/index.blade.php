@extends('admin::layouts.master')
@section('pageTitle')
    Megrendelések
@endsection

@section('header')
    @include('orders::layouts.header', ['title' => 'Megrendelések', 'subtitle' => 'Összes'])
@endsection

@section('js')
@endsection
@section('css')
    <style>
        .select2-selection--single {
            margin-left: 20px !important;
        }
    </style>
@endsection
@section('content')
    @livewire('orders::listcomponent')
@endsection
