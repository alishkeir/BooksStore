@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->title }} szerkesztése
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('templates::_formjs')    @isset($model->store )
    <script>
        $('[data-domain]').hide();
        $('[data-domain={{$model->store}}]').show();
    </script>
@endisset
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Sablonok', 'subtitle' => 'Szerkesztés'])
@endsection

@section('content')

    @include('templates::_form')

@endsection
