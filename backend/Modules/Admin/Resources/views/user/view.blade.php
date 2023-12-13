@extends('admin::layouts.master')
@section('pageTitle')
    {{ $user->name }} megtekintése
@endsection
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Profile</h5>
        <div class="card-body">
        </div>
    </div>
</div>
<div class="content-wrapper">
    <div class="profile-cover">
        <div class="media-body text-white">
            <h1 class="mb-0">{{$user->name}}</h1>
        </div>
    </div>
</div>

@endsection