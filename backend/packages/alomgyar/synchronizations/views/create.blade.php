@extends('admin::layouts.master')
@section('pageTitle')
    Új Synchronizations létrehozása
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('synchronizations::_formjs')
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Synchronizations', 'subtitle' => __('general.add-new')])
@endsection

@section('content')

    @include('synchronizations::_form')

@endsection
