@extends('admin::layouts.master')
@section('pageTitle')
    Felhasználó létrehozása
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Felhasználók', 'subtitle' => 'Új hozzáadása'])
@endsection
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Új felhasználó hozzáadása</h5>
    </div>
    <div class="card-body">
        <form action="{{route('user.store')}}" method="POST">
            @include('admin::user._form')
        </form>
    </div>
</div>

@endsection