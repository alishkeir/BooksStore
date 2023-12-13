@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->full_name }} szerkesztése
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('customers::_formjs')
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Ügyfelek', 'subtitle' => $model->full_name . ' adatlapja'])
@endsection

@section('content')

    @include('customers::_form')

@endsection
