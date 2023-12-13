@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->title }} szerkesztése
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('shops::_formjs')
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Könyvesboltok', 'subtitle' => 'Szerkesztés'])
@endsection

@section('content')

    @include('shops::_form')

@endsection
