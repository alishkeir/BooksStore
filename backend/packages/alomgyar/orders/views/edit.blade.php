@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->order_number }}
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">

    <style>
        .table-lg td,
        .table-lg th {
            padding: 5px;
            ;
        }

        .page-title h4 {
            font-size: 35px;
            margin-top: -11px;
            margin-bottom: -11px;
        }
    </style>
@endsection
@section('js')
    @include('orders::_formjs')
    @isset($model->store)
        <script>
            $('[data-domain]').hide();
            $('[data-domain={{ $model->store }}]').show();
        </script>
    @endisset
    
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => $model->order_number, 'subtitle' => ''])
@endsection

@section('content')
    @livewire('orderpage', ['orderNumber' => $model->order_number, 'onlyEbook' => $onlyEbook])
@endsection
