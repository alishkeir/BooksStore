@isset($model)
<form action="{{route('writers.update', ['writer' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('writers.store')}}" method="POST" enctype="multipart/form-data" id="form">
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

                @if(($model ?? false) && ($model->created_at ?? false))
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                    <span class="text-muted text-right">Módosítva: {{ $model->updated_at->diffForHumans() }}</span>
                </div>
                @endif
            </div>
            
            <!-- Authors -->
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="text-uppercase font-size-sm font-weight-semibold">Szerzők</span>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="media-list author">
                        @if($model ?? false)
                            @foreach($model->author ?? [] as $writerauthor)
                            @if($writerauthor)
                            <li class="media">
                                <div class="mr-3 align-self-center">
                                    <i class="icon-quill4 text-success-300 top-0"></i>
                                </div>
                                <div class="media-body">
                                    <div class="font-weight-semibold">
                                        <select name="author[]" class="form-control select-search" data-fouc >
                                            @if($writerauthor->id)
                                                <option value="{{ $writerauthor->id }}"
                                                        selected="selected">{{ $writerauthor->title }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="ml-2 align-self-center">
                                    <a href="javascript:" onclick="$(this).parent().parent().remove()" class="icon icon-trash text-dark"></a>
                                </div>
                            </li>
                            @endif
                            @endforeach
                        @endif
                    </ul>
                    <a href="javascript:" title="Szerző hozzáadása" onClick="$('.media-list.author').append($('#li-tpl-author').html()); $('.author .newselect').addClass('select-search').removeClass('newselect'); const eventSelectRestart = new Event('restartSelect2'); window.dispatchEvent(eventSelectRestart);" class="btn btn-outline bg-success btn-icon text-success border-success border-2 rounded-round legitRipple mt-3">
                        <i class="icon-plus3"></i></a>
                </div>
            </div>
            <!-- /Authors -->
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

<div style="display:none;" id="li-tpl-author">
    <li class="media">
        <div class="mr-3 align-self-center">
            <i class="icon-quill4 text-warning-300 top-0"></i>
        </div>
        <div class="media-body">
            <div class="font-weight-semibold">
                <select class="form-control author newselect" data-fouc name="author[]">
                </select>
            </div>
        </div>
        <div class="ml-2 align-self-center">
            <a href="javascript:" onclick="$(this).parent().parent().remove()" class="icon icon-trash text-dark"></a>
        </div>
    </li>
</div>
