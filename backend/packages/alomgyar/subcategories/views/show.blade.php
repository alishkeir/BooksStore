@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->title }} megtekintése
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
    @include('admin::layouts.header', ['title' => 'Alkategóriák', 'subtitle' => 'View', 'button' => route('subcategories.create')])
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{$model->id}}</td>
                </tr>
                <tr>
                    <th>Név</th>
                    <td>{{$model->title}}</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

@endsection
