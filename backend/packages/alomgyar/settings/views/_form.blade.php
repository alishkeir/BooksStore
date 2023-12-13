@isset($model)
    <form action="{{ route('settings.update', ['setting' => $model->id]) }}" method="POST" enctype="multipart/form-data"
        id="form">
        @method('PUT')
    @else
        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" id="form">
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
                                <label class="col-form-label font-weight-bold">Cím</label>
                                <input name="title" id="title"
                                    class="form-control @error('title') border-danger @enderror"
                                    value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Azonosító</label>
                                <input name="key" id="key"
                                    class="form-control @error('key') border-danger @enderror"
                                    value="{{ !is_null(old('key')) ? old('key') : $model->key ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Section</label>
                                <select name="section" id="section"
                                    class="form-control pl-2 @error('section') border-danger @enderror"
                                    value="{{ !is_null(old('section')) ? old('section') : $model->section ?? '' }}">
                                    @forelse (\App\helpers\SettingsHelper::SETTING_SECTIONS as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @empty
                                        <option value="">nincsenek szakaszok</option>
                                    @endforelse
                                </select>
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
                                    <input type="checkbox" name="status" value="1" class="form-check-input"
                                        @if ($model->status ?? false) checked="" @endif>
                                </label>
                            </div>
                        </div>
                    </div>
                    {{--
                <div class="card-body">

                </div> --}}

                    @if ($model ?? false)
                        <div class="card-footer d-flex justify-content-between">
                            <span class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                            <span class="text-muted text-right">Módosítva:
                                {{ $model->updated_at->diffForHumans() }}</span>
                        </div>
                    @endif
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
