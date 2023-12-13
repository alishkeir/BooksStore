@extends('admin::layouts.no-nav')
@section('pageTitle')
    Könyv kiajánló
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Könyv kiajánló', 'subtitle' => ''])
@endsection

@section('js')
@endsection
@section('css')
@endsection
@section('content')
    <div>
        <h6>Üdvözöllek {{ auth()->user()?->name }}</h6>
    </div>
    @livewire('bookrecommendation::component')
@endsection
