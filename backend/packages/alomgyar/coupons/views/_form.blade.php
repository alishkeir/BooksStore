@isset($model)
<form action="{{route('coupons.update', ['coupon' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('coupons.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Prefix</label>
                            <input name="prefix" id="prefix" class="form-control @error('prefix') border-danger @enderror" value="{{ !is_null(old('prefix')) ? old('prefix') : $model->prefix ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Kód</label>
                            <input name="code" id="code" class="form-control @error('code') border-danger @enderror" value="{{ !is_null(old('code')) ? old('code') : $model->code ?? '' }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Kedvezmény típus</label>
                                    <select class="form-control" name="is_percent">
                                        <option val="">Százalék</option>
                                        <option val="">Összeg</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Kedvezmény</label>
                                    <input name="code" id="code" type="number" class="form-control @error('code') border-danger @enderror" value="{{ !is_null(old('code')) ? old('code') : $model->code ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Célfelhasználó:</label>
                                    <select class="form-control" name="is_percent">
                                        <option val="">Mindenki</option>
                                        <option val="">Specifikus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Ügyfél kód:</label>
                                    <input name="code" id="code" type="number" class="form-control @error('code') border-danger @enderror" value="{{ !is_null(old('code')) ? old('code') : $model->code ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="card-title font-weight-semibold">Paraméterek</span>
                    <div class="header-elements">
                        <div class="form-check form-check-right">
                            <label class="form-check-label">
                                Megjelenik
                                <input type="checkbox" name="status" value="1" class="form-check-input" @if($model->status ?? false) checked="" @endif>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="col-form-label font-weight-bold">Felhasználható</label>
                            <input name="free_count"  class="form-control daterange-time @error('free_count') border-danger @enderror" value="">
                            @error('free_count')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label font-weight-bold">Ennyiszer felhaszálva</label>
                            <h6>0</h6>
                            @error('free_count')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Érvényesség (<small>{{ !is_null(old('active_from')) ? old('active_from') : $model->active_from ?? '' }} - {{ !is_null(old('active_to')) ? old('active_to') : $model->active_to ?? '' }}</small>)</label>
                            <input name="active"  class="form-control daterange-time @error('active_from') border-danger @enderror" value="">
                            @error('active_from')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="card-title font-weight-semibold">Érvényes itt:</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="store_0" value="1" class="form-check-input" @if($model->store_0 ?? false) checked="" @endif>
                                    Álomgyár
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="store_1" value="1" class="form-check-input" @if($model->store_1 ?? false) checked="" @endif>
                                    Olcsókönyvek
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="store_2" value="1" class="form-check-input" @if($model->store_2 ?? false) checked="" @endif>
                                    Nagyker
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                @if($model ?? false)
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                    <span class="text-muted text-right">Módosítva: {{ $model->updated_at->diffForHumans() }}</span>
                </div>
                @endif
            </div>
            
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5>Leírás (nem jelenik meg sehol)</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <textarea name="description" id="description" class="form-control @error('description') border-danger @enderror">{{ !is_null(old('description')) ? old('description') : $model->description ?? '' }}</textarea>
                            @error('description')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
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
