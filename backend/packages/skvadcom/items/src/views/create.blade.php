@extends('admin::layouts.master')
@section('pageTitle')
    Új Items létrehozása
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('items::_formjs')
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Items', 'subtitle' => __('general.add-new')])
@endsection

@section('content')

    @include('items::_form')

@endsection
