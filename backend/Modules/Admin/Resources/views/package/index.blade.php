@extends('admin::layouts.master')

@section('header')
    @include('admin::layouts.header', ['title' => 'Package', 'subtitle' => 'Összes package', 'button' => route('packages.create')])
@endsection

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Package-k</h5>
    </div>
    @if(Session::has('flash_message'))
    <div class="alert alert-success border-0 alert-dismissibl mb-2">
        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        <span class="font-weight-semibold">{{ Session::get('flash_message') }}
        </span>
    </div>
    @endif
    <div class="card-body">
        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Név</th>
                    <th>Mappa</th>
                    <th>Fields</th>
                    <th>Resource</th>
                    <th class="text-center">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($model as $m)
                <tr>
                    <td>{{$m->id}}</td>
                    <td>{{$m->name}}</td>
                    <td>{{$m->folder}}</td>
                    <td>
                        {{$m->fields}}
                    </td>
                    <td>{{$m->resource}}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            {{-- <a href="{{ route('packages.show', ['package' => $m->id]) }}" class="list-icons-items text-teal-600"><i class="icon-eye2"></i></a> --}}
                            {{-- <a href="{{ route('packages.edit', ['package' => $m->id]) }}" class="list-icons-items text-primary-600"><i class="icon-pencil7"></i></a> --}}
                            @hasrole('skvadmin')
                            <form action="{{ route('packages.destroy', ['package' => $m->id, 'type' => 'delete']) }}" class="d-inline" method="POST" onsubmit="return confirm({{ __('messages.delete-confirm') }});">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-link list-icons-items text-danger-600 p-0"> <i class="icon-trash"></i></button>
                            </form>
                            @endhasrole
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="datatable-footer">
        <div class="dataTables_paginate paging_simple_numbers">
            {{-- {{ $model->links() }} --}}
        </div>
    </div>
</div>

@endsection