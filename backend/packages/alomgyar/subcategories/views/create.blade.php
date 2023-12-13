@extends('admin::layouts.master')
@section('pageTitle')
    Új Subcategories létrehozása
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('subcategories::_formjs')
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Alkategória', 'subtitle' => __('general.add-new')])
@endsection

@section('content')

    @include('subcategories::_form')

@endsection
