@extends('admin::layouts.master')
@section('pageTitle')
    Aktivitás lista
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Adatbázis események', 'subtitle' => ''])
@endsection

@section('content')

@livewire('logs::listcomponent')

@endsection

@section('js')
    @include('admin::partials._packagejs')
@endsection