@extends('admin::layouts.master')
@section('pageTitle')
    Magazon
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Magazin', 'subtitle' => 'Összes bejegyzés', 'button' => route('posts.create')])
@endsection

@section('content')

    {{--@livewire('posts::listcomponent')--}}
     @livewire('posts::cards') 

@endsection
