@extends('admin::layouts.master')
@section('pageTitle')
    Ajánló megtekintése
@endsection
@section('js')

@endsection

@section('css')
    <style>
        .table th {
            width: 200px;
        }
    </style>
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Ajánló', 'subtitle' => null, 'button' => route('recommenders.create')])
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-lg-2 text-lg-right font-weight-bold">Célzott terméket vásárlók</div>
            <div class="col-sm-12 col-lg-10">{{ $recommender->originalProduct->title }}</div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-2 text-lg-right font-weight-bold">Ajánlott termék</div>
            <div class="col-sm-12 col-lg-10">{{ $recommender->promotedProduct->title }}</div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-2 text-lg-right font-weight-bold">Küldés időponja - tényleges kiküldés</div>
            <div class="col-sm-12 col-lg-10">{{ $recommender->release_date }} - {{ $recommender->released_at }}</div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-2 text-lg-right font-weight-bold">Levél tárgya: </div>
            <div class="col-sm-12 col-lg-10">{{ $recommender->subject }}</div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-2 text-lg-right font-weight-bold">Levél tárgya: </div>
            <div class="col-sm-12 col-lg-10">{{ $recommender->message_body }}</div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-2 text-lg-right font-weight-bold">Elért felhasználók száma</div>
            <div class="col-sm-12 col-lg-10">{{ $customerNum }}</div>
        </div>

    </div>
</div>

@endsection
