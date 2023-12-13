@extends('admin::layouts.master')
@section('pageTitle')
    Új ajánlás létrehozása
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('recommenders::_formjs')
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Ajánlás', 'subtitle' => __('general.add-new')])
@endsection

@section('content')

    @include('recommenders::_form', ['recommender' => $recommender])

@endsection
