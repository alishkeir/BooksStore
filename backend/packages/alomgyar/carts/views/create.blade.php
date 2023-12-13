@extends('admin::layouts.master')
@section('pageTitle')
    Új Carts létrehozása
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('carts::_formjs')
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Carts', 'subtitle' => __('general.add-new')])
@endsection

@section('content')

    @include('carts::_form')

@endsection
