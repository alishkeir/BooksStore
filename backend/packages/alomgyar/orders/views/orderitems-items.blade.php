@extends('admin::layouts.master')
@section('pageTitle')
    Teljesíthető -
@endsection

@section('header')
    @include('orders::layouts.header', ['title' => 'Megrendelés Tételek', 'subtitle' => 'Kezelendő tételek'])
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

    @livewire('orders::listitemcomponent', ['type'=>'items'])

@endsection
