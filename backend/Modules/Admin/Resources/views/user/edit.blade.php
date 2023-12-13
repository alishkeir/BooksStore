@extends('admin::layouts.master')
@section('pageTitle')
    {{ $user->name }} szerkesztése
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Felhasználók', 'subtitle' => 'Szerkesztés '. $user->lastname . ' ' . $user->firstname, 'button' => route('user.create')])
@endsection
@section('content')
<div class="card">
    <div class="card-body">

    <div class="tab-content">
        <form id="data-basic" class="tab-pane fade show active" action="{{ route('user.update', ['user' => $user->id]) }}" method="POST">
        @method('PUT')
            @csrf
            @include('admin::user._form', ['user' => $user])
        </form>
        </div>

    </div>
</div>

@endsection