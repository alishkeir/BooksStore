@extends('admin::layouts.master')
@section('pageTitle')
    Synchronizations
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Szinkronizációk', 'subtitle' => 'Lista'])
@endsection

@section('content')

    @livewire('synchronizations::synccomponent')

@endsection
