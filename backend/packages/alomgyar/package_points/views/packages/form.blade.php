@extends('admin::layouts.master')
@section('pageTitle')
    Csomagpont csomag
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Csomagpont', 'subtitle' => $package->id ? 'Csomag szerkesztése' : 'Csomag létrehozása', 'button' => route('package-points.package.create')])
@endsection

@section('content')
    <form action="{{ $formRoute }}" method="POST">

        @if($package->id)
            {{ method_field('PUT') }}
        @endif

        {{ csrf_field() }}

        <div class="col-md-8 col-12">

            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="code">Rendelés szám</label>
                        <input type="text" name="code" class="form-control" value="{{ $package->code ?? old('code') }}">
                        @error('code')
                        <span class="form-text text-danger">A mező kitöltése kötelező.</span>
                        @enderror
                    </div>
                    <div class="form-group"><label for="partner">Partner (feladási hely)</label>
                        <select id="partner" class="form-control" name="partner_id">
                            @foreach(\Alomgyar\PackagePoints\Models\PackagePointPartner::all() as $partner)
                                <option value="{{ $partner->id }}"
                                        @if($package->partner_id == $partner->id) selected @endif>{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shop" class="font-weight-bold">Shop (átvételi hely)</label>
                        <select id="shop" class="form-control" name="shop_id">
                            @foreach(\Alomgyar\PackagePoints\Models\PackagePointShop::all() as $shop)
                                <option value="{{ $shop->id }}"
                                        @if($package->shop_id == $shop->id) selected @endif>{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status" class="font-weight-bold">Státusz</label>
                        <select id="status" class="form-control" name="status">
                            @foreach(\Alomgyar\PackagePoints\Entity\Enum\Status::toArray() as $key => $status)
                                <option value="{{ $key }}"
                                        @if(old('status') ?? $package->status === $key) selected @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="customer">Megrendelő</label>
                        <input type="text" name="customer" class="form-control"
                               value="{{ $package->customer ?? old('customer') }}">
                        @error('customer')
                        <span class="form-text text-danger">A mező kitöltése kötelező.</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="code">Email</label>
                        <input type="text" name="email" class="form-control"
                               value="{{ $package->email ?? old('email') }}">
                        @error('email')
                        <span class="form-text text-danger">A mező kitöltése kötelező.</span>
                        @enderror
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
