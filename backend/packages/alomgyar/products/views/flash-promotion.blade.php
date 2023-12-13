@extends('admin::layouts.master')
@section('pageTitle')
    Törzsadatok
@endsection


@section('header')
    @include('admin::layouts.header', ['title' => 'Könyvek', 'subtitle' => 'Törzsadatok'])
@endsection

@section('content')
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
            <livewire:products::flashcomponent/>
    </div>
</div>


@endsection
