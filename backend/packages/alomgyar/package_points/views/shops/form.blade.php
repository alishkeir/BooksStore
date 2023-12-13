@extends('admin::layouts.master')
@section('pageTitle')
    Csomagpont Shop
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Csomagpont', 'subtitle' => $shop->id ? $shop->name . ' szerkesztése' : 'Shop létrehozása', 'button' => route('package-points.shops.create')])
@endsection

@section('content')

    <form action="{{ $formRoute }}" method="POST">

        @if($shop->id)
            {{ method_field('PUT') }}
        @endif

        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-8 col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-form-label font-weight-bold">Név</label>
                            <input name="name" id="name" class="form-control @error('name') border-danger @enderror" value="{{ $shop->name }}">
                            @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label font-weight-bold">Cím</label>
                            <input name="address" id="address" class="form-control @error('address') border-danger @enderror" value="{{ $shop->address }}">
                            @error('address')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label font-weight-bold">Nyitvatartás</label>
                            <textarea name="open" id="open" class="form-control @error('open') border-danger @enderror">{!! $shop->open !!}</textarea>
                            @error('open')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label font-weight-bold">Telefonszám</label>
                            <input name="phone" id="phone" class="form-control @error('phone') border-danger @enderror" value="{{ $shop->phone }}">
                            @error('phone')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label font-weight-bold">Email</label>
                            <input name="email" id="email" class="form-control @error('email') border-danger @enderror" value="{{ $shop->email }}">
                            @error('email')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right">
                <li>
                    <button type="submit"
                            class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple"
                            title="{{ __('messages.save') }}">
                        <i class="fab-icon-open icon-paperplane"></i>
                    </button>
                </li>
            </ul>
        </div>
    </form>
@endsection
