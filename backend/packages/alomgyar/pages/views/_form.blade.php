@isset($model)
<form action="{{route('pages.update', ['page' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('pages.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Cím</label>
                            <input name="title" id="title" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                        </div>
                    </div>
{{--}}
                    <div class="form-group row">
                        <label class="col-form-label font-weight-bold">Cím</label>
                        <div class="col-lg-12">
                            <div class="dropzone" data-type="page" data-url="{{url(route('fileupload'))}}">
                                <div class="dz-message" data-dz-message style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;"><span>Húzd ide a képet, vagy kattints a feltöltéshez</span></div>
                                <img src="{{ old('cover') ?? '/storage/'.($model->cover ?? '') }}" width="100%" class="preview" style="width:100%;"/>
                            </div>
                            <input type="text" class="form-control" name="cover" value="{{ old('cover') ?? ($model->cover ?? '')}}">
                        </div>
                    </div>--}}
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Leírás</label>
                            <textarea name="body" id="description" class="summernote form-control @error('body') border-danger @enderror">{{ !is_null(old('body')) ? old('body') : $model->body ?? '' }}</textarea>
                            @error('body')
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
                                <input type="checkbox" name="status" value="1" class="form-check-input" @if($model->status ?? false) checked="" @endif>
                            </label>
                        </div>
                    </div>
                </div>
                {{--
                <div class="card-body">
                
                </div>--}}

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
                    <h5>SEO</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Slug</label>
                            <input name="slug" id="slug" class="form-control @error('slug') border-danger @enderror" value="{{ !is_null(old('slug')) ? old('slug') : $model->slug ?? '' }}">
                            @error('slug')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Meta title</label>
                            <input name="meta_title" id="meta_title" class="form-control @error('meta_title') border-danger @enderror" value="{{ !is_null(old('meta_title')) ? old('meta_title') : $model->meta_title ?? '' }}">
                            @error('meta_title')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Meta description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control @error('meta_description') border-danger @enderror">{{ !is_null(old('meta_description')) ? old('meta_description') : $model->meta_description ?? '' }}</textarea>
                            @error('meta_description')
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
<style>
    .dropzone{
        display:block;
    }
    .dropzone img{
        max-height:200px!important;
        width:auto!important;
    }
</style>