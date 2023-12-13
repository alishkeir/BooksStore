@extends('admin::layouts.master')
@section('pageTitle')
    Új Products létrehozása
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('products::_formjs')
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Könyvek', 'subtitle' => __('general.add-new')])
@endsection

@section('content')
        @include('products::_form')
@endsection
