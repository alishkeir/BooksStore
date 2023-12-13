@isset($model)
<form action="{{route('legal_owners.update', ['legal_owner' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('legal_owners.store')}}" method="POST" enctype="multipart/form-data" id="form">
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
                            <label class="col-form-label font-weight-bold">Jogtulajdonos neve</label>
                            <input name="title" id="title" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Jutalék</label>
                            <input name="commission" id="commission" class="form-control @error('commission') border-danger @enderror" value="{{ !is_null(old('commission')) ? old('commission') : $model->commission ?? '' }}">
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
