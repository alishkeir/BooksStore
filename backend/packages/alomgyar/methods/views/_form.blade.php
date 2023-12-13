@isset($model)
<form action="{{ $route }}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('methods.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5>Tartalom</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Megnevezés</label>
                            <input name="name" id="name" class="form-control @error('name') border-danger @enderror" value="{{ !is_null(old('name')) ? old('name') : $model->name ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Álomgyár díj</label>
                            <input name="fee_0" id="fee_0" class="form-control @error('fee_0') border-danger @enderror" value="{{ !is_null(old('fee_0')) ? old('fee_0') : $model->fee_0 ?? '' }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Olcsókönyvek díj</label>
                            <input name="fee_1" id="fee_1" class="form-control @error('fee_1') border-danger @enderror" value="{{ !is_null(old('fee_1')) ? old('fee_1') : $model->fee_1 ?? '' }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Nagyker díj</label>
                            <input name="fee_2" id="fee_2" class="form-control @error('fee_2') border-danger @enderror" value="{{ !is_null(old('fee_2')) ? old('fee_2') : $model->fee_2 ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Álomgyár kedvezményes díj</label>
                            <input name="discounted_fee_0" id="fee_0" class="form-control @error('discounted_fee_0') border-danger @enderror" value="{{ (!is_null(old('discounted_fee_0')) ? old('discounted_fee_0') : ($model->discounted_fee_0 ?? '')) }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Olcsókönyvek kedvezményes díj</label>
                            <input name="discounted_fee_1" id="fee_1" class="form-control @error('discounted_fee_1') border-danger @enderror" value="{{ (!is_null(old('discounted_fee_1')) ? old('discounted_fee_1') : ($model->discounted_fee_1 ?? '')) }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Nagyker kedvezményes díj</label>
                            <input name="discounted_fee_2" id="fee_2" class="form-control @error('discounted_fee_2') border-danger @enderror" value="{{ (!is_null(old('discounted_fee_2')) ? old('discounted_fee_2') : ($model->discounted_fee_2 ?? '')) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">

                            <div class="form-check form-check-left">
                                <label class="form-check-label">
                                    <input type="checkbox" name="status_0" value="1" class="form-check-input" @if($model->status_0 ?? false) checked="" @endif>
                                    Megjelenik
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4">

                            <div class="form-check form-check-left">
                                <label class="form-check-label">
                                    <input type="checkbox" name="status_1" value="1" class="form-check-input" @if($model->status_1 ?? false) checked="" @endif>
                                    Megjelenik
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            
                            <div class="form-check form-check-left">
                                <label class="form-check-label">
                                    <input type="checkbox" name="status_2" value="1" class="form-check-input" @if($model->status_2 ?? false) checked="" @endif>
                                    Megjelenik
                                </label>
                            </div>
                        </div>
                    </div>
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
{{--}}
                    <div class="header-elements">
                        <div class="form-check form-check-right">
                            <label class="form-check-label">
                                Megjelenik
                                <input type="checkbox" name="status" value="1" class="form-check-input" @if($model->status ?? false) checked="" @endif>
                            </label>
                        </div>
                    </div>--}}
                </div>

                <div class="card-body">
                    Azonosító kulcs: {{$model->method_id}}
                </div>

                @if($model ?? false)
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                    <span class="text-muted text-right">Módosítva: {{ $model->updated_at->diffForHumans() }}</span>
                </div>
                @endif
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
