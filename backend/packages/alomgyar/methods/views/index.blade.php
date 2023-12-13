@extends('admin::layouts.master')
@section('pageTitle')
    Fizetési és szállítási módok
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Fizetési és szállítási módok', 'subtitle' => ''])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            @livewire('methods::cards')
        </div>
        <div class="col-md-6">
            @livewire('methods::cards-shipping')
        </div>
    </div>
@endsection
