@isset($model)
<form action="{{route('warehouses.update', ['warehouse' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('warehouses.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Elnevezés</label>
                            <input name="title" id="title" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row my-5">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Cím</label>
                            <input name="address" id="address" class="form-control @error('address') border-danger @enderror" value="{{ !is_null(old('address')) ? old('address') : $model->address ?? '' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label font-weight-bold">Irányítószám</label>
                                    <input name="zip_code" id="zip_code" class="form-control @error('zip_code') border-danger @enderror" value="{{ !is_null(old('zip_code')) ? old('zip_code') : $model->zip_code ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label font-weight-bold">Város</label>
                                    <input name="city" id="city" class="form-control @error('city') border-danger @enderror" value="{{ !is_null(old('city')) ? old('city') : $model->city ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Leírás</label>
                            <textarea name="description" id="description" class="summernote form-control @error('description') border-danger @enderror">{{ !is_null(old('description')) ? old('description') : $model->description ?? '' }}</textarea>
                            @error('description')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
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

                    <div class="header-elements">
                        <div class="form-check form-check-right">
                            <label class="form-check-label">
                                Megjelenik
                                <input type="checkbox" name="status" value="1" class="form-check-input" @if($model->status ?? true) checked="" @endif>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Telefon</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="phone" id="phone" class="form-control @error('phone') border-danger @enderror" value="{{ !is_null(old('phone')) ? old('phone') : $model->phone ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Email</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="email" id="email" class="form-control @error('email') border-danger @enderror" value="{{ !is_null(old('email')) ? old('email') : $model->email ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Előtag</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="invoice_prefix" id="invoice_prefix" class="form-control @error('invoice_prefix') border-danger @enderror" value="{{ !is_null(old('invoice_prefix')) ? old('invoice_prefix') : $model->invoice_prefix ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Bolt</label>
                        <select class="form-control select-search" data-fouc data-placeholder="Válassz egyet..." name="shop_id">
                            <option></option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" @if(isset($model) && $shop->id == $model->shop_id) selected @endif>{{ $shop->title }}</option>
                            @endforeach
                        </select>
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
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">
                        <i class="icon-calculator mr-2"></i>
                        Számlázási adatok
                    </h6>

                    <div class="header-elements">
                        <div class="form-check form-check-right">
                            <label class="form-check-label font-weight-semibold">
                                Kereskedői
                                <input type="checkbox" name="is_merchant" value="1" class="form-check-input" @if($model->is_merchant ?? false) checked="" @endif>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Cégnév</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="billing_business_name" id="billing_business_name" class="form-control @error('billing_business_name') border-danger @enderror" value="{{ !is_null(old('billing_business_name')) ? old('billing_business_name') : $model->billing_business_name ?? '' }}">
                        </div>
                        @error('billing_business_name')
                        <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Adószám</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="billing_vat_number" id="billing_vat_number" class="form-control @error('billing_vat_number') border-danger @enderror" value="{{ !is_null(old('billing_vat_number')) ? old('billing_vat_number') : $model->billing_vat_number ?? '' }}">
                        </div>
                        @error('billing_vat_number')
                        <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Város</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="billing_city" id="billing_city" class="form-control @error('billing_city') border-danger @enderror" value="{{ !is_null(old('billing_city')) ? old('billing_city') : $model->billing_city ?? '' }}">
                        </div>
                        @error('billing_city')
                        <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Irányítószám</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="billing_zip_code" id="billing_zip_code" class="form-control @error('billing_zip_code') border-danger @enderror" value="{{ !is_null(old('billing_zip_code')) ? old('billing_zip_code') : $model->billing_zip_code ?? '' }}">
                        </div>
                        @error('billing_zip_code')
                        <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Cím</label>
                        </div>
                        <div class="col-lg-8">
                            <input name="billing_address" id="billing_address" class="form-control @error('billing_address') border-danger @enderror" value="{{ !is_null(old('billing_address')) ? old('billing_address') : $model->billing_address ?? '' }}">
                        </div>
                        @error('billing_address')
                        <span class="form-text text-danger">{{$message}}</span>
                        @enderror
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
