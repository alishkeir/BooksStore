@isset($model)
<form action="{{route('promotions.update', ['promotion' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('promotions.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="col-form-label font-weight-bold">Cover kép (pl 2000x400)</label>
                            <div class="dropzone" data-type="promotion" data-url="{{url(route('fileupload'))}}">
                                <div class="dz-message" data-dz-message style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;"><span>Húzd ide a képet, vagy kattints a feltöltéshez</span></div>
                                <img src="{{ old('cover') ?? '/storage/'.(isset($model) ? $model->cover : '') }}" width="100%" class="preview" style="width:100%;"/>

                            </div>
                            <input type="text" class="form-control" name="cover" value="{{ old('cover') ?? (isset($model) ? $model->cover : '')}}">
                        </div>

                        <div class="col-lg-3">
                            <label class="col-form-label font-weight-bold">Lista kép (xl) (pl 640x360)</label>

                            <div class="dropzone" data-type="promotion/xl" data-url="{{url(route('fileupload'))}}">
                                <div class="dz-message" data-dz-message style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;"><span>Húzd ide a képet, vagy kattints a feltöltéshez</span></div>
                                <img src="{{ old('list_image_xl') ?? '/storage/'.(isset($model) ? $model->list_image_xl : '') }}" width="100%" class="preview" style="width:100%;"/>
                            </div>
                            <input type="text" class="form-control" name="list_image_xl" value="{{ old('list_image_xl') ?? (isset($model) ? $model->list_image_xl : '')}}">

                        </div>
                        <div class="col-lg-3">
                            <label class="col-form-label font-weight-bold">Lista kép (sm) (pl 400x320)</label>

                            <div class="dropzone" data-type="promotion/sm" data-url="{{url(route('fileupload'))}}">
                                <div class="dz-message" data-dz-message style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;"><span>Húzd ide a képet, vagy kattints a feltöltéshez</span></div>
                                <img src="{{ old('list_image_sm') ?? '/storage/'.(isset($model) ? $model->list_image_sm : '') }}" width="100%" class="preview" style="width:100%;"/>
                            </div>
                            <input type="text" class="form-control" name="list_image_sm" value="{{ old('list_image_sm') ?? (isset($model) ? $model->list_image_sm : '')}}">


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            @isset($model)
            <div class="card card-body border-top-1 border-top-primary">
                @livewire('promotions::products', ['promotion_id'=>$model->id] )
            </div>
            @endisset
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="card-title font-weight-semibold">Paraméterek</span>
                    <div class="header-elements">
                        <div class="form-check form-check-right">
                            <label class="form-check-label">
                                Megjelenik
                                <input type="checkbox" name="status" value="1" class="form-check-input" @if(isset($model) && $model->status) checked="" @endif>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Cím</label>
                            <input name="title" id="title" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('title')) ? old('title') : (isset($model) ? $model->title : '') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Slug</label>
                            <input name="slug" id="slug" class="form-control @error('slug') border-danger @enderror" value="{{ !is_null(old('slug')) ? old('slug') : (isset($model) ? $model->slug : '') }}">
                            @error('slug')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Érvényesség (<small>{{ !is_null(old('active_from')) ? old('active_from') : (isset($model) ? $model->active_from : '') }} - {{ !is_null(old('active_to')) ? old('active_to') : (isset($model) ? $model->active_to : '') }}</small>)</label>
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
                                    <input type="checkbox" name="store_0" value="1" class="form-check-input" @if(isset($model) && $model->store_0) checked="" @endif>
                                    Álomgyár
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="store_1" value="1" class="form-check-input" @if(isset($model) && $model->store_1) checked="" @endif>
                                    Olcsókönyvek
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="store_2" value="1" class="form-check-input" @if(isset($model) && $model->store_2) checked="" @endif>
                                    Nagyker
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                @if(isset($model))
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

<div class="modal fade" id="addto" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Termékek hozzáadása excelből</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{route('process-addtopromotion')}}" method="POST">
                <div class="modal-body">
                    <div class="alert alert-primary border-0 alert-dismissible mb-2">
                        <span class="font-weight-semibold">Fontos!</span>  Sikeres termékimport esetén az összes korábbi hozzárendelés és akcióhoz tartozó ár elveszik
                        <br>Könyv listát a <a href="/gephaz/products">Könyvek</a> menüpontnál lehet exportálni
                    </div>
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="promotion" value="{{ isset($model) ? $model->id : ''}}">
                        <div class="form-group">
                                <div class="invalid-feedback"></div>
                                <div class="dropzonefile" data-type="import" data-url="{{url(route('file-upload'))}}">
                                    <div class="dz-message" data-dz-message
                                        style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;">
                                        <span>Húzd ide az import file-t</span></div>

                                </div>
                                <input type="hidden" name="importfile">
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button"  class="btn btn-link" data-dismiss="modal">Mégsem</button>
                    <button type="submit"  class="btn bg-primary" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card .dropzonefile {
        background-color: #fcfcfc;
        border-color: #ddd;
        display: flex;
        flex-direction: column;
        position: relative;
        border: 2px dashed rgba(0, 0, 0, .125);
        min-height: 40px;
        min-width: 100%;
        background-color: #fff;
        padding: .3125rem;
        border-radius: .1875rem;
        margin-top: 10px;
    }

    .dz-preview > * {
        display: none
    }

    .dz-preview .dz-details {
        display: block;
        width: 100%;
    }

    .dz-started .dz-message {
        display: none !important;
    }
    .modal-body .dz-message{
        display:block;
        position: relative!important;
        border:2px dashed;
    }
</style>

