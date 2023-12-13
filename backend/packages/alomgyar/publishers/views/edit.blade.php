@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->title }} szerkesztése
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('publishers::_formjs')
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Publishers', 'subtitle' => 'Szerkesztés'])
@endsection

@section('content')

    @include('publishers::_form')

@endsection
