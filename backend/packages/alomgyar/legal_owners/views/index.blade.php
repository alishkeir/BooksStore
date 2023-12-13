@extends('admin::layouts.master')
@section('pageTitle')
    Jogtulajdonosok
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Jogtulajdonosok', 'subtitle' => 'Összes Jogtulajdonos', 'button' => route('legal_owners.create')])
@endsection

@section('content')

    @livewire('legal_owners::listcomponent')
    {{-- @livewire('publishers::cards') --}}

@endsection
