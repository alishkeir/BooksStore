@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->title }} szerkesztése
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('product_movements::_formjs')
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Product_movements', 'subtitle' => 'Szerkesztés'])
@endsection

@section('content')

    @include('product_movements::_form')

@endsection
