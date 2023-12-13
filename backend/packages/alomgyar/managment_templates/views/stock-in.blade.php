@extends('admin::layouts.master')
@section('pageTitle')
    Új Posts létrehozása
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('posts::_formjs')
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Bevételezés'])
@endsection

@section('content')

<form>
    <div class="row">
        <div class="col-md-12">

            <div class="card card-body">
                <div class="form-group row">
                    <div class="col-lg-3">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Termék ISBN vagy ID</label>
                                <input name="published_at" type="number"  class="form-control @error('published_at') border-danger @enderror" value="{{ !is_null(old('published_at')) ? old('published_at') : $model->published_at ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="col-form-label font-weight-bold">Termék</label>
                        <select class="form-control">
                            <option>Listából kiválasztott termék</option>
                            <option>Termék 2</option>
                            <option>Termék 3</option>
                            <option>Termék 4</option>
                            <option>Termék 5</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold"> </label>
                                <br><a href="#" class="btn btn-info">Alkalmaz</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">
                        <i class="icon-folder6 mr-2"></i>
                        Forrás
                    </h6>
                    <div class="header-elements">
                        <span class="text-muted"></span>
                    </div>
                </div>
                <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0 mt-3">
                    <li class="nav-item"><a href="#tab-spec" class="nav-link legitRipple" data-toggle="tab">Raktár</a></li>
                    <li class="nav-item"><a href="#tab-shipping" class="nav-link legitRipple active show" data-toggle="tab">Beszerzés</a></li>
                </ul>
                <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
                    <div class="tab-pane fade" id="tab-spec">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Raktár</label>
                                    <select class="form-control">
                                        <option>Raktár 1 (234)</option>
                                        <option>Központi raktár (234)</option>
                                        <option>Webshop raktár (234)</option>
                                    </select>
                                </div>
                            </div>
                                
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Mennyiség (max 234 db)</label>
                                    <input name="published_at" type="number"  class="form-control @error('published_at') border-danger @enderror" value="{{ !is_null(old('published_at')) ? old('published_at') : $model->published_at ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade active show" id="tab-shipping">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Beszállító</label>
                                    
                                    <select class="form-control">
                                        <option>Beszállító 1</option>
                                        <option>Beszállító 2</option>
                                        <option>Beszállító 3</option>
                                        <option>Beszállító 4</option>
                                        <option>Beszállító 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Beszerzési ár (Ft)</label>
                                    <input name="published_at" id="published_at" class="form-control @error('published_at') border-danger @enderror" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Mennyiség</label>
                                    <input name="published_at" type="number"  class="form-control @error('published_at') border-danger @enderror" value="{{ !is_null(old('published_at')) ? old('published_at') : $model->published_at ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">
                        <i class="icon-folder6 mr-2"></i>
                        Cél
                    </h6>
                    <div class="header-elements">
                        <span class="text-muted"></span>
                    </div>
                </div>
                <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0 mt-3">
                    <li class="nav-item"><a href="#tab-one" class="nav-link legitRipple active show" data-toggle="tab">Raktár</a></li>
                    
                </ul>
                <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
                    <div class="tab-pane fade active show" id="tab-two">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Raktár</label>
                                    <select class="form-control">
                                        <option>Raktár 1</option>
                                        <option>Központi raktár</option>
                                        <option>Webshop raktár</option>
                                    </select>
                                </div>
                            </div>
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
