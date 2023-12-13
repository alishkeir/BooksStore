@extends('admin::layouts.master')
@section('pageTitle')
    Majdnem teljesíthető rendelések - 
@endsection

@section('header')
    @include('orders::layouts.header', ['title' => 'Megrendelés Tételek', 'subtitle' => 'Szállítást igénylő rendelések'])
@endsection

@section('js')
@endsection
@section('css')
<style>
.select2-selection--single {
    margin-left: 20px!important;
}
</style>
@endsection
@section('content')

    @livewire('orders::listitemcomponent', ['type'=>'almost'])

@endsection
