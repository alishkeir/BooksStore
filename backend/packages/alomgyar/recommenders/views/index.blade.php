@extends('admin::layouts.master')
@section('pageTitle')
    Recommenders
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Ajánlások', 'subtitle' => 'Lefutott ajánlások', 'button' => route('recommenders.create')])
@endsection

@section('content')

    @livewire('recommenders::listcomponent')

@endsection
