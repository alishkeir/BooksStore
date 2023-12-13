@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->reference_nr }} megtekintése
@endsection
@section('js')

@endsection

@section('css')
    <style>
        .table th {
            width: 200px;
        }
    </style>
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Bizonylat adatlap', 'subtitle' => 'Megtekint'])
@endsection

@section('content')
@php
    $totalPrice = 0
@endphp

    @if($model->is_canceled)
        <div class="alert alert-warning bg-white alert-styled-left alert-dismissible">
            Ezt a bizonylatot sztornózták korábban.
        </div>
    @endif

<div class="card">
    <div class="card-body">
        <h3>
            @if($model->destination_type === 6)
                Szállítólevél
            @else
                Raktárkészlet változás bizonylat
            @endif
            <div class="float-right">Bizonylat sorszáma: {{ $model->reference_nr }}</div>
        </h3>
        @if($model->source_type === 'storno')
            <div class="float-right">{{ $model->comment_general }}</div>
        @endif
        <br><br>

        <table class="table table-striped">
            <thead>
            <tr>
                <th># </th>
                <th>Típus</th>
                <th>
                    @if($model->destination_type === 6)
                        Szállító
                    @else
                        Forrás
                    @endif
                </th>
                <th>
                    @if($model->destination_type === 6)
                        Vevő
                    @else
                        Cél
                    @endif
                </th>
                <th>Létrehozás</th>
                <th>Megjegyzés</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $model->id }}</td>
                    <td>{{ $model->type }}</td>
                    <td>
                        @if($model->destination_type === \Alomgyar\Product_movements\ProductMovement::DESTINATION_TYPE_MERCHANT)
                            Publish and More Kft. <br>
                            1137 Budapest, Pozsonyi út 10.
                        @else
                            {{ $model->source?->title ?? '' }}
                        @endif
                    </td>
                    <td>
                        @if(in_array($model->destination_type,[1,2]))
                            Vásárló
                        @else
                            {{  $model->destination?->title ?? 'Egyéb' }}
                        @endif
                            <br>
                        {{ $model->destination?->fullAddress ?? '' }}
                            <br>
                        {{ $model->comment_void ?? '' }}
                    </td>
                    <td>
                        {{ $model->created_at->format('Y-m-d H:i') }}
                        <br><small>{{ $model->causer?->full_name }}</small>
                    </td>
                    <td>
                        {{ $model->comment_general }}
                    </td>
                    <td class="text-right">
                        <div class="list-icons">
                            <div class="btn-group ml-2">
                                <button type="button" class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></button>

                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">

                                    @if(in_array($model->destination_type,[1,2]))
                                        @if($model->destination_id)
                                        <a href="{{ route('orders.edit', ['order' => $model->destination_id]) }}" target="_blank" class="dropdown-item">Rendeléslap megnyitása</a>
                                        @else
                                            @if (\Illuminate\Support\Str::contains($model->comment_general, 'sztornó bizonylata'))
                                            <a href="{{ route('orders.edit', ['order' => \App\Order::where('order_number', Str::before($model->comment_general, ' sz. rendelés sztornó bizonylata'))->first()?->id]) }}" target="_blank" class="dropdown-item">Rendeléslap megnyitása</a>
                                            @else
                                            <a href="{{ route('orders.edit', ['order' => \App\Order::where('order_number', Str::before($model->comment_general, ' sz. rendelés sztornózva'))->first()?->id]) }}" target="_blank" class="dropdown-item">Rendeléslap megnyitása</a>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('product_movements.export', ['ProductMovement' => $model]) }}" class="dropdown-item">Szállítólevél készítés</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>
        <br><br>
        <table style="width: 100%" class="table table-stiped">
            <thead>
            <tr>
                <th>Megnevezés</th>
                <th>Termék kód</th>
                <th>Mennyiség</th>
                <th>Listaár</th>
                <th>Ebből lejelentett</th>
            </tr>
            </thead>
            <tbody>
            @foreach($model->productItems as $item)
                @php
                    $totalPrice += $item->product?->prices?->price_list
                @endphp
                <tr>
                    <td><strong>{{ $item->product?->title }}</strong></td>
                    <td>{{ $item->product?->isbn }}</td>
                    <td>{{ $item->stock_in > 0 ? $item->stock_in : '-' . $item->stock_out }} db</td>
                    <td>{{ $item->product?->prices?->price_list }} Ft</td>
                    <td>{{ is_null($item->remaining_quantity_from_report) ? 0 : ($item->stock_in > 0 ? $item->stock_in - $item->remaining_quantity_from_report : 'N/A') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>Összesen</strong></td>
                <td><strong>
                        @if($model->productItems->sum('stock_in') > 0)
                            {{ $model->productItems->sum('stock_in') }}
                        @else
                            - {{ $model->productItems->sum('stock_out') }}
                        @endif
                        db</strong></td>
                <td><strong>{{ $totalPrice }} Ft</strong></td>
                <td>&nbsp;</td>
            </tr>
            </tbody>
        </table>
        @if ($model->comment_bottom ?? false)
        <div class="pt-3">
            <h5>Megjegyzés (alsó):</h5>
            <p>{{ $model->comment_bottom ?? '' }}</p>
        </div>
        @endif
        @if ($model->comment_general ?? false)
        <div class="pt-3">
            <h5>Megjegyzés (általános):</h5>
            <p>{{ $model->comment_general ?? '' }}</p>
        </div>
        @endif
        @if ($model->comment_void ?? false)
        <div class="pt-3">
            <h5>Megjegyzés (adminisztrátori):</h5>
            <p>{{ $model->comment_void ?? '' }}</p>
        </div>
        @endif

    </div>
</div>

@endsection
