@extends('admin::layouts.master')
@section('pageTitle')
    Új Megrendelések létrehozása
@endsection
@section('css')
    <style>
        .dropzonefile {
            background-color: #fcfcfc;
            border-color: #ddd;
            display: flex;
            flex-direction: column;
            position: relative;
            border: 2px dashed rgba(0, 0, 0, .125);
            min-height: 40px;
            min-width: 100%;
            background-color: #fff;
            padding: .3125rem;
            border-radius: .1875rem;
            margin-top: 10px;
        }

        .dz-preview>* {
            display: none
        }

        .dz-preview .dz-details {
            display: block;
            width: 100%;
        }

        .dz-started .dz-message {
            display: none !important;
        }

        .dz-message {
            line-height: unset;
        }
    </style>
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Megrendelések', 'subtitle' => __('general.add-new')])
@endsection

@section('content')
    @livewire('orders::createcomponent')
@endsection