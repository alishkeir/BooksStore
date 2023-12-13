@isset($model)
<form action="{{route('posts.update', ['post' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('posts.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body" style="min-height:348px;">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Kiemelt kép</label>

                                    <div class="dropzone" data-type="post" data-url="{{url(route('fileupload'))}}">
                                        <div class="dz-message" data-dz-message style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;"><span>Húzd ide a képet, vagy kattints a feltöltéshez</span></div>
                                        <img src="{{ old('cover') ?? '/storage/'.($model->cover ?? '') }}" width="100%" class="preview" style="width:100%;"/>
                                    </div>
                                    <input type="text" class="form-control" name="cover" value="{{ old('cover') ?? ($model->cover ?? '')}}">


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Cím</label>
                                    <input name="title" id="title" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Dátum</label>
                                    <input name="published_at" id="published_at" class="form-control @error('published_at') border-danger @enderror" value="{{ !is_null(old('published_at')) ? old('published_at') : $model->published_at ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Bevezető</label>
                                    <textarea name="lead" id="lead" class="form-control @error('lead') border-danger @enderror">{{ !is_null(old('lead')) ? old('lead') : $model->lead ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5>Tartalom</h5>
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Leírás</label>
                            <textarea name="body" id="description" class="form-control @error('body') border-danger @enderror">{{ !is_null(old('body')) ? old('body') : $model->body ?? '' }}</textarea>
                            @error('body')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
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
