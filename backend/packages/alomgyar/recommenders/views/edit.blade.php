@extends('admin::layouts.master')
@section('pageTitle')
    {{ $recommender->title }} szerkesztése
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('recommenders::_formjs')
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Ajánlás', 'subtitle' => 'Szerkesztés'])
@endsection

@section('content')

    @include('recommenders::_form')

@endsection
