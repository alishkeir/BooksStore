@isset($model)
<form action="{{route('comments.update', ['comment' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('comments.store')}}" method="POST" enctype="multipart/form-data" id="form">
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
                            <label class="col-form-label font-weight-bold">Hozzászólás ideje</a>)</label>
                            <input name="created_at" id="created_at" class="form-control @error('created_at') border-danger @enderror" value="{{ !is_null(old('created_at')) ? old('created_at') : $model->created_at ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Hozzászólás</label>
                            <textarea name="comment" id="comment" class="summernote form-control @error('comment') border-danger @enderror">{{ !is_null(old('comment')) ? old('comment') : $model->comment ?? '' }}</textarea>
                            @error('comment')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    @if($model->comment != $model->original_comment)
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Eredeti Hozzászólás</label>
                            <textarea name="original_comment" id="original_comment" class="summernote form-control @error('original_comment') border-danger @enderror">{{ !is_null(old('original_comment')) ? old('original_comment') : $model->original_comment ?? '' }}</textarea>
                            @error('original_comment')
                            <span class="form-text text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    @endif
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
                               Állapot: 
                                
                                <select name="status">
                                    <option value="0" @if(($model->status ?? false) == 0) selected @endif>Tiltott</option>
                                    <option value="1" @if(($model->status ?? false) == 1) selected @endif>Új</option>
                                    <option value="2" @if(($model->status ?? false) == 2) selected @endif>Aktív</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <p>
                        Melyik bolthoz: <strong>
                        @if($model->store == 0) Álomgyár @endif
                        @if($model->store == 1) Olcsókönyvek @endif
                        @if($model->store == 2) Nagyker @endif
                        </strong>
                    </p>
                    <p>
                        Melyik termékhez: <a href="{{ route('products.edit', ['product' => $model->product]) }}">{{$model->product->title}}</a>
                    </p>
                    <p>
                        Hozzászóló: <a href="{{ route('customers.edit', ['customer' => $model->customer]) }}">{{$model->customer->username}}</a>
                    </p>
                </div>

                @if($model ?? false)
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">Létrehozva: {{ $model->created_at?->diffForHumans() }}</span>
                    <span class="text-muted text-right">Módosítva: {{ $model->updated_at?->diffForHumans() }}</span>
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
