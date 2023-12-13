@extends('admin::layouts.master')
@section('pageTitle')
    Csomagpont Partner
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Csomagpont', 'subtitle' => $partner->id ? 'Partner szerkesztése' : 'Partner létrehozása', 'button' => route('package-points.partners.create')])
@endsection

@section('content')

    <form action="{{ $formRoute }}" method="POST">

        @if($partner->id)
            {{ method_field('PUT') }}
        @endif

        {{ csrf_field() }}

        <div class="col-md-8 col-12">

            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-form-label font-weight-bold">Név</label>
                        <input name="name" id="name" class="form-control " value="{{ $partner->name }}">
                        @error('address')
                        <span name="form-text text-danger">A mező kitöltése kötelező.</span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label font-weight-bold">Link</label>
                        <input name="link" id="link" class="form-control " value="{{ $partner->link }}">
                        @error('link')
                        <span class="form-text text-danger">A mező kitöltése kötelező.</span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label font-weight-bold">Email</label>
                        <input name="email" id="email" class="form-control " value="{{ $partner->email }}">
                        @error('email')
                        <span class="form-text text-danger">A mező kitöltése kötelező.</span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label font-weight-bold">Telefonszám</label>
                        <input name="phone" id="phone" class="form-control " value="{{ $partner->phone }}">
                        @error('phone')
                        <span class="form-text text-danger">A mező kitöltése kötelező.</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right">
                <li>
                    <button type="submit" class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple" title="{{ __('messages.save') }}">
                        <i class="fab-icon-open icon-paperplane"></i>
                    </button>
                </li>
            </ul>
        </div>
    </form>
@endsection
