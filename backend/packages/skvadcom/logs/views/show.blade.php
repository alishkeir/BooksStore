@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->id }} megtekintése
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Log', 'subtitle' => 'Bejegyzés megtekintése', 'button' => ''])
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <table class="table datatable-basic table-striped" data-page-length='25'>
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Tartalom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-weight-semibold">ID</td>
                    <td>{{$model->id}}</td>
                </tr>
                <tr>
                    <td class="font-weight-semibold">Leírás</td>
                    <td>{{$model->description}}</td>
                </tr>
                <tr>
                    <td class="font-weight-semibold">Entitás</td>
                    <td>{{$model->subject_type}}</td>
                </tr>
                <tr>
                    <td class="font-weight-semibold">Entitás ID</td>
                    <td>{{$model->subject_id}}</td>
                </tr>
                <tr>
                    <td class="font-weight-semibold">Felhasználó</td>
                    <td>{{$model->created}}</td>
                </tr>
                <tr>
                    <td class="font-weight-semibold">Adatok</td>
                    <td>
                        @foreach (json_decode($model->properties) as $key => $item)
                            @php 
                                $item = (array) $item;
                            @endphp
                            @if (is_array($item))
                                @foreach ($item as $k => $i)
                                    @if (is_array($i))
                                        @foreach ($i as $l => $j)
                                        @if (is_array($l) || is_array($j))
                                            <li>{{ json_encode($l) }} => {{ json_encode($j) }}</li>
                                        @else
                                            <li>{{ $l }} => {{ $j }}</li>
                                        @endif
                                        @endforeach
                                    @elseif (is_object($i))
                                        <li>{{ $k }} => {{ json_encode($i) }}</li>
                                    @else
                                        <li>{{ $k }} => {{ $i }}</li>
                                    @endif
                                @endforeach
                            @else
                                {{ var_dump($item) }}
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-semibold">Készült</td>
                    <td>{{$model->created_at}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('js')
    @include('admin::partials._packagejs')
@endsection