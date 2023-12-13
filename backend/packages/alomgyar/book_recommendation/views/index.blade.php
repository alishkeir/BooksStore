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
    <div class="card">
        {{-- <div class="card-header ">
            Bejelentkezés
        </div> --}}
        <div class="card-body">
            <form action="{{ route('recommendation.post') }}" method="POST">
                @csrf
                <div>
                    <label>Email cím</label>
                    <input type="text" name="email" class="form-control" value="{{ old('email') }}" />
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>Jelszó</label>
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}" />
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-2">
                    <button class="btn btn-xs btn-success" type="submit">Belépés</button>
                </div>
            </form>
        </div>
    </div>
@endsection
