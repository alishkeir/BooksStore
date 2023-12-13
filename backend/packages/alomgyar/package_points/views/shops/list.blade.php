@extends('admin::layouts.master')
@section('pageTitle')
    Csomagpont Partner
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => '', 'subtitle' => 'Összes', 'button' => route('package-points.shops.create')])
@endsection

@section('content')
    <div class="card">

        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <td>Név</td>
                    <td>Link</td>
                    <td>Nyitvatartás</td>
                    <td>Telefonszám</td>
                    <td class="text-right">Műveletek</td>
                </tr>
                </thead>
                <tbody>
                @foreach($shops as $shop)
                    <tr>
                        <td>{{ $shop->name ?? '' }}</td>
                        <td>{{ $shop->address ?? '' }}</td>
                        <td>{!! $shop->open ?? '' !!}</td>
                        <td>{{ $shop->phone ?? '' }}</td>
                        <td class="text-right">
                            <div class="list-icons">
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></button>

                                    <div class="dropdown-menu" style="">
                                        <a href="{{ route('package-points.shops.edit', $shop) }}" class="dropdown-item">Szerkesztés</a>
                                        <a href="#" class="dropdown-item">Törlés</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
