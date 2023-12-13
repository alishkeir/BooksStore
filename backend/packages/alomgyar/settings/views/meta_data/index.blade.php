@extends('admin::layouts.master')
@section('pageTitle')
    Settings Meta Data
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Beállítások meta címkék', 'subtitle' => 'Minden beállítás', 'button' => route('metadata.create')])
@endsection

@section('content')
    @livewire('metadata::listcomponent')
@endsection
