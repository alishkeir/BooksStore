@extends('admin::layouts.master')
@section('pageTitle')
    Affiliates
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Affiliates', 'subtitle' => 'All affiliates'])
@endsection

@section('content')
    @livewire('affiliates::listcustomers')
    <div class="card">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="actual">
                    <div><b>ÉRTÉKESÍTÉSI JUTALÉKOK</b></div>
                    <hr />
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <a role="button" class="text-default">
                                        Kifizetésre váró affiliate jutalék jóváírások összesen
                                    </a>
                                </th>
                                <th>
                                    <a role="button" class="text-default">
                                        Kifizetett értékesítési jutalékok ősszesen
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $unpaidCredit }}</td>
                                <td>{{ $paidCredit }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @livewire('affiliates::listredeems')
@endsection
