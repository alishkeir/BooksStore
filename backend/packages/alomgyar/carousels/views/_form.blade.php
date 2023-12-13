@isset($model)
    <form action="{{route('carousels.update', ['carousel' => $model])}}" method="POST" enctype="multipart/form-data"
          id="form">
        @method('PUT')
        @else
            <form action="{{route('carousels.store')}}" method="POST" enctype="multipart/form-data" id="form">
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
                                        <label class="col-form-label font-weight-bold">kép</label>

                                        <livewire:carousels::uploadimage
                                                path="carousel"
                                                acceptedFiles="image"
                                                fieldName="image"
                                                modelId="{{ isset($model) && !empty($model->getMedia()) ? $model->id : null }}"
                                        />

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label class="col-form-label font-weight-bold" for="store">Bolt</label>
                                        <select id="store" class="form-control" name="shop_id">
                                            <option value="0">Álomgyár</option>
                                            <option value="1">Olcsó könyvek</option>
                                            <option value="2">Álomgyár nagyker</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label class="col-form-label font-weight-bold" for="url">URL</label>
                                        <input name="url" id="url"
                                               class="form-control @error('url') border-danger @enderror"
                                               value="{{ !is_null(old('url')) ? old('url') : $model->url ?? '' }}">
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
