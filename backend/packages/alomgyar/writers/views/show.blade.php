@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->title }} megtekintése
@endsection

@section('js')
    @include('writers::_formjs')
@endsection

@section('css')
    <style>
        .table th {
            width: 200px;
        }
    </style>
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => $model->title, 'subtitle' => ' fogyásjelentései'])
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5>Fogyásjelentések</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>

                            <th>#</th>
                            <th>Időszak</th>
{{--                            <th>Termékek száma</th>--}}
                            <th class="text-center">Jelentések letöltése</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Időszak</th>
{{--                            <th>Termékek száma</th>--}}
                            <th class="text-center">Jelentések letöltése</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($reports as $report)
                         <tr>
                            <td>{{ $report['id'] }}</td>
                            <td>{{ $report['period'] }}</td>
{{--                            <td>24409</td>--}}
                            <td class="text-center" style="white-space: nowrap">
                                <a href="/gephaz{{ \Illuminate\Support\Facades\Storage::disk('local')->url('author-consumption-reports/' . $report['file']) }}" role="button" class="text-default"
                                    download
                                    >
                                    <i class="icon-file-download"></i>
                                    <span>{{ $report['file'] }}</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card card-collapsed">
            <div class="card-header bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">Aktuális hónap fogyásai</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Könyv címe</th>
                        <th class="text-center">ISBN</th>
                        <th class="text-center">Értékesített mennyiség</th>
                        <th class="text-center">Számlázandó (nettó) egységár</th>
                        <th class="text-center">Összesen nettó számlázandó</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Könyv címe</th>
                        <th class="text-center">ISBN</th>
                        <th class="text-center">Értékesített mennyiség</th>
                        <th class="text-center">Számlázandó (nettó) egységár</th>
                        <th class="text-center">Összesen nettó számlázandó</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($books as $item)
                        <tr>
                            <td>{{ $item['product_title'] }}</td>
                            <td class="text-center">{{ $item['isbn'] }} / {{ $item['product_id'] }}</td>
                            <td class="text-center">{{ $item['total_sales'] }}</td>
                            <td class="text-right">@huf($item['author_commission'])</td>
                            <td class="text-right">@huf($item['total_amount'])</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title font-weight-semibold">
                    <i class="icon-cabinet mr-2"></i>
                    Paraméterek
                </h6>

            </div>

            @if(($model ?? false) && ($model->created_at ?? false))
            <div class="card-footer d-flex justify-content-between">
                <span class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                <span class="text-muted text-right">Módosítva: {{ $model->updated_at->diffForHumans() }}</span>
            </div>
            @endif
        </div>

        <!-- Authors -->
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">{{$model->title}}</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="media-list author">
                    @if($model ?? false)
                        @foreach($model->author ?? [] as $writerauthor)
                        @if($writerauthor)
                        <li class="media">
                            <div class="mr-3 align-self-center">
                                <i class="icon-quill4 text-success-300 top-0"></i>
                            </div>
                            <div class="media-body">
                                <div class="font-weight-semibold">
                                    {{ $writerauthor->title }}
                                </div>
                            </div>

                        </li>
                        @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <!-- /Authors -->
    </div>
</div>

@endsection
