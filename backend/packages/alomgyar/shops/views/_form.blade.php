@isset($model)
    <form action="{{ route('shops.update', ['shop' => $model]) }}" method="POST" enctype="multipart/form-data" id="form">
        @method('PUT')
    @else
        <form action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data" id="form">
        @endisset
        @csrf
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5>Tartalom</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Megnevezés</label>
                                <input name="title" id="title"
                                    class="form-control @error('title') border-danger @enderror"
                                    value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Kiemelt kép</label>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Kép</label>
                                    <div class="col-lg-12">
                                        <div class="dropzone" data-type="shop"
                                            data-url="{{ url(route('fileupload')) }}">
                                            <div class="dz-message" data-dz-message
                                                style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;">
                                                <span>Húzd ide a képet, vagy kattints a feltöltéshez</span>
                                            </div>
                                            <img src="{{ old('cover') ?? '/storage/' . ($model->cover ?? '') }}"
                                                width="100%" class="preview" style="width:100%;" />
                                        </div>
                                        <input type="text" class="form-control" name="cover"
                                            value="{{ old('cover') ?? ($model->cover ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row my-5">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Cím</label>
                                <input name="address" id="address"
                                    class="form-control @error('address') border-danger @enderror"
                                    value="{{ !is_null(old('address')) ? old('address') : $model->address ?? '' }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label font-weight-bold">Irányítószám</label>
                                        <input name="zip_code" id="zip_code"
                                            class="form-control @error('zip_code') border-danger @enderror"
                                            value="{{ !is_null(old('zip_code')) ? old('zip_code') : $model->zip_code ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label font-weight-bold">Város</label>
                                        <input name="city" id="city"
                                            class="form-control @error('city') border-danger @enderror"
                                            value="{{ !is_null(old('city')) ? old('city') : $model->city ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Leírás (nem jelenik meg sehol)</label>
                                <textarea name="description" class="summernote form-control @error('description') border-danger @enderror">{{ !is_null(old('description')) ? old('description') : $model->description ?? '' }}</textarea>
                                @error('description')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
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
                                    <input type="checkbox" name="status" value="1" class="form-check-input"
                                        @if ($model->status ?? false) checked="" @endif>
                                </label>
                            </div>
                        </div>
                        <div class="header-elements">
                            <div class="ml-2 form-check form-check-right">
                                <label class="form-check-label">
                                    Szállításnál látható
                                    <input type="checkbox" name="show_shipping" value="1" class="form-check-input"
                                        @if ($model->show_shipping ?? false) checked="" @endif>

                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Érvényes itt</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="store_0" value="1"
                                                class="form-check-input"
                                                @if ($model->store_0 ?? false) checked="" @endif>
                                            Álomgyár
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="store_1" value="1"
                                                class="form-check-input"
                                                @if ($model->store_1 ?? false) checked="" @endif>
                                            Olcsókönyvek
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="store_2" value="1"
                                                class="form-check-input"
                                                @if ($model->store_2 ?? false) checked="" @endif>
                                            Nagyker
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Telefon</label>
                            </div>
                            <div class="col-lg-8">
                                <input name="phone" id="phone"
                                    class="form-control @error('phone') border-danger @enderror"
                                    value="{{ !is_null(old('phone')) ? old('phone') : $model->phone ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Email</label>
                            </div>
                            <div class="col-lg-8">
                                <input name="email" id="email"
                                    class="form-control @error('email') border-danger @enderror"
                                    value="{{ !is_null(old('email')) ? old('email') : $model->email ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Facebook</label>
                            </div>
                            <div class="col-lg-8">
                                <input name="facebook" id="facebook"
                                    class="form-control @error('facebook') border-danger @enderror"
                                    value="{{ !is_null(old('facebook')) ? old('facebook') : $model->facebook ?? '' }}">
                            </div>
                        </div>
                    </div>

                    @if ($model ?? false)
                        <div class="card-footer d-flex justify-content-between">
                            <span class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                            <span class="text-muted text-right">Módosítva:
                                {{ $model->updated_at->diffForHumans() }}</span>
                        </div>
                    @endif
                </div>
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title font-weight-semibold">
                            <i class="icon-map mr-2"></i>
                            Koordináták
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Latitude</label>
                            </div>
                            <div class="col-lg-8">
                                <input name="latitude" id="latitude"
                                    class="form-control @error('latitude') border-danger @enderror"
                                    value="{{ !is_null(old('latitude')) ? old('latitude') : $model->latitude ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Longitude</label>
                            </div>
                            <div class="col-lg-8">
                                <input name="longitude" id="longitude"
                                    class="form-control @error('longitude') border-danger @enderror"
                                    value="{{ !is_null(old('longitude')) ? old('longitude') : $model->longitude ?? '' }}">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title font-weight-semibold">
                            <i class="icon-alarm-check mr-2"></i>
                            Nyitvatartás
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row mb-0">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Első</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="opening_hours[0][days]" id="slug" class="form-control"
                                            placeholder="Hétfő-Kedd"
                                            value="{{ $model->opening_hours[0]['days'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="opening_hours[0][hours]" id="slug" class="form-control"
                                            placeholder="9:00 - 19:00"
                                            value="{{ $model->opening_hours[0]['hours'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Második</label>
                                <div class="row">
                                    <div class="col-md-6"><input name="opening_hours[1][days]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[1]['days'] ?? '' }}"></div>
                                    <div class="col-md-6"><input name="opening_hours[1][hours]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[1]['hours'] ?? '' }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Harmadik</label>
                                <div class="row">
                                    <div class="col-md-6"><input name="opening_hours[2][days]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[2]['days'] ?? '' }}"></div>
                                    <div class="col-md-6"><input name="opening_hours[2][hours]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[2]['hours'] ?? '' }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Negyedik</label>
                                <div class="row">
                                    <div class="col-md-6"><input name="opening_hours[3][days]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[3]['days'] ?? '' }}"></div>
                                    <div class="col-md-6"><input name="opening_hours[3][hours]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[3]['hours'] ?? '' }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Ötödik</label>
                                <div class="row">
                                    <div class="col-md-6"><input name="opening_hours[4][days]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[4]['days'] ?? '' }}"></div>
                                    <div class="col-md-6"><input name="opening_hours[4][hours]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[4]['hours'] ?? '' }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Hatodik</label>
                                <div class="row">
                                    <div class="col-md-6"><input name="opening_hours[5][days]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[5]['days'] ?? '' }}"></div>
                                    <div class="col-md-6"><input name="opening_hours[5][hours]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[5]['hours'] ?? '' }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Hetedik</label>
                                <div class="row">
                                    <div class="col-md-6"><input name="opening_hours[6][days]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[6]['days'] ?? '' }}"></div>
                                    <div class="col-md-6"><input name="opening_hours[6][hours]" id="slug"
                                            class="form-control"
                                            value="{{ $model->opening_hours[6]['hours'] ?? '' }}"></div>
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
                    <button type="submit"
                        class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple"
                        title="{{ __('messages.save') }}">
                        <i class="fab-icon-open icon-paperplane"></i>
                    </button>
                </li>
            </ul>
        </div>
    </form>
