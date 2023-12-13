@extends('admin::layouts.master')
@section('pageTitle')
    Settings
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Beállítások', 'subtitle' => 'Minden beállítás', 'button' => route('settings.create')])
@endsection

@section('content')
    @livewire('settings::listcomponent')
@endsection
