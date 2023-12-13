@extends('admin::layouts.master')
@section('pageTitle')
    Ügyvitel sablonok
@endsection

@section('header')
    @include('managment_templates::layouts.header', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Megrendelések', 'button' => route('managment_templates.create')])
@endsection

@section('content')

Számlázó,
<br>Bolti eladás
<br>Készletkezelő
<br>  -  Leltár
<br>Raktár kezelő,
<br>Fogyás statisztikák,
<br>Szerző fogyás statisztika,
<br>Beszállító kezelő
<br>Megrendelések
<br>  -  Futárszolgálat export
<br>  -  szamlazz hu szinkron
<br>Teljesíthető megrendelések listája
<br>Nem teljesíthető megrendelések listája
<br>Fogyásjelentések
<br>  -  Exportok listája
<br>  -  Jelentések generálása




    {{-- @livewire('managment_templates::listcomponent') @livewire('managment_templates::cards') --}}

@endsection
