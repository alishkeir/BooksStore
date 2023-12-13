@extends('admin::layouts.master')

@section('pageTitle', 'Banner - ')

@section('content')
    <div>
        @livewire('banners::form')
    </div>
@endsection