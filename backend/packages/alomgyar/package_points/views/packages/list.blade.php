@extends('admin::layouts.master')
@section('pageTitle')
    Csomagpont Csomagok
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Csomagpont', 'subtitle' => 'Összes csomag', 'button' => route('package-points.package.create')])
@endsection

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Szűrő</h4>
                </div>

                <form>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="partner" class="font-weight-bold">Szabadszavas</label>
                            <input type="text" name="slug" class="form-control" value="{{ request()->query('slug') }}">
                        </div>
                        <div class="form-group"><label for="partner" class="font-weight-bold">Partner (feladási hely)</label>
                            <select id="partner_id" class="form-control" name="partner_id">
                                <option value="">Mind</option>
                                @foreach(\Alomgyar\PackagePoints\Models\PackagePointPartner::all() as $partner)
                                    <option value="{{ $partner->id }}"
                                            @if((int)request()->query('partner') === $partner->id) selected @endif>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="shop_id" class="font-weight-bold">Shop (átvételi hely)</label>
                            <select id="shop_id" class="form-control" name="shop_id">
                                <option value="">Mind</option>
                                @foreach(\Alomgyar\PackagePoints\Models\PackagePointShop::all() as $shop)
                                    <option value="{{ $shop->id }}"
                                            @if((int)request()->query('shop') === $shop->id) selected @endif>{{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Státusz</label>
                            <select id="status" class="form-control" name="status">
                                <option value="">Mind</option>
                                @foreach(\Alomgyar\PackagePoints\Entity\Enum\Status::toArray() as $key => $status)
                                    <option value="{{ $key }}"
                                            @if(request()->query('status') === $key) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-primary btn-block">Keresés</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Találatok</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Státusz</td>
                            <td>Partner (feladási hely)</td>
                            <td>Rendelés szám</td>
                            <td>Név</td>
                            <td>Shop (átvételi hely)</td>
                            <td>Átvéve</td>
                            <td>Létrehozva</td>
                            <td>Utolsó módosítás</td>
                            <td class="text-right">Műveletek</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ \Alomgyar\PackagePoints\Entity\Enum\Status::from($package->status)->label }}</td>
                                <td>{{ $package->partner->name ?? 'N/A' }}</td>
                                <td>{{ $package->code }}</td>
                                <td>{{ $package->customer }}</td>
                                <td>{{ $package->shop->name }}</td>
                                <td>{{ $package->collected ?? 'Nincs' }}</td>
                                <td>{{ $package->created_at }}</td>
                                <td>{{ $package->updated_at }}</td>
                                <td class="text-right">
                                    <div class="list-icons">
                                        <div class="btn-group ml-2">
                                            <button type="button"
                                                    class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                    data-toggle="dropdown" aria-expanded="false"><i
                                                        class="icon-menu7"></i></button>

                                            <div class="dropdown-menu" style="">
                                                <a href="{{ route('package-points.package.edit', $package) }}"
                                                   class="dropdown-item">Szerkesztés</a>
                                                <a href="#" class="dropdown-item">Törlés</a>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="my-3 w-100 justify-content-center">
                        <div>
                            {{ $packages->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
