@extends('admin::layouts.master')
@section('pageTitle')
Jogosultságkezelő
@endsection

@section('header')
@include('admin::layouts.header', ['title' => 'Jogosultságkezelő', 'subtitle' => 'Új jogosultsági kör'])
@endsection

@section('content')

<form action="{{route('role.store')}}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">{{__('permissions::trans.role.name')}}</label>
                            <input name="name" id="name" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('name')) ? old('name') : $model->name ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right">
            <li>
                <button type="submit" class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple" title="{{ __('messages.save') }}">
                    <i class="fab-icon-open icon-paperplane"></i>
                </button>
            </li>
        </ul>
    </div>
</form>

@endsection