@isset($model)
<form action="{{route('subcategories.update', ['subcategory' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('subcategories.store')}}" method="POST" enctype="multipart/form-data" id="form">
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
                            <input name="title" id="title" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Slug</label>
                            <input name="slug" id="slug" class="form-control @error('slug') border-danger @enderror" value="{{ !is_null(old('slug')) ? old('slug') : $model->slug ?? '' }}">
                            @error('slug')
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

                @if($model ?? false)
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">Létrehozva: {{ $model->created_at?->diffForHumans() }}</span>
                    <span class="text-muted text-right">Módosítva: {{ $model->updated_at?->diffForHumans() }}</span>
                </div>
                @endif
            </div>

                <!-- Categories -->

                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-size-sm font-weight-semibold">Szülő kategóriák</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="media-list subcategory">
                            @isset($model)
                               @foreach($model->categories ?? [] as $parentcategory)
                                @if($parentcategory)
                                <li class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-price-tags text-success-300 top-0"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-weight-semibold">
                                            <select name="category[]" class="form-control select @error('category') border-danger @enderror">
                                                <option  value="">Üres</option>
                                                @foreach ($categories ?? [] as $item)
                                                    <option @if($item->id == $parentcategory->id) selected @endif value="{{ $item->id }}">
                                                        {{ $item->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                            @endisset
                        </ul>
                        <a href="javascript:" title="Alkategória hozzáadása" onClick="$('.media-list.subcategory').append($('#li-tpl').html()); $('.subcategory .newselect').select2(); $('.subcategory .newselect').removeClass('newselect');" class="btn btn-outline bg-success btn-icon text-success border-success border-2 rounded-round legitRipple mt-3">
                            <i class="icon-plus3"></i></a>
                    </div>
                </div>

                <!-- /Categories -->
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



<div style="display:none;" id="li-tpl">
    <li class="media">
        <div class="mr-3 align-self-center">
            <i class="icon-price-tags text-warning-300 top-0"></i>
        </div>
        <div class="media-body">
            <div class="font-weight-semibold">
                <select name="category[]" class="form-control newselect @error('category') border-danger @enderror">
                    <option  value="">Üres</option>
                    @foreach ($categories ?? [] as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </li>
</div>
