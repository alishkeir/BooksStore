@extends('admin::layouts.master')
@section('pageTitle')
    Fogyásjelentések
@endsection

@section('header')
    @include('admin::layouts.header', [
        'title' => 'Fogyásjelentések',
        'subtitle' => 'Összes időszak',
        // 'startMonth' => true,
        // 'selectedMonth' => true,
        // 'button' => route('consumption_report.author-regenerate'),
        // 'buttonText' => 'SZERZŐI fogyásjelentések újragenerálása',
        // 'button2' => route('consumption_report.legal-regenerate'),
        // 'buttonText2' => 'JOGTULAJDONOS fogyásjelentések újragenerálása',
    ])
@endsection

@section('content')
    @livewire('consumption_reports::listcomponent')
@endsection
