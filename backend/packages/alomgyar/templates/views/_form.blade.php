@isset($model)
<form action="{{ route('templates.update', ['template' => $model]) }}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('templates.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Sablon</label>
                            <textarea name="description" id="description" class="summernote form-control @error('description') border-danger @enderror">{{ !is_null(old('description')) ? old('description') : $model->description ?? '' }}</textarea>
                            @error('description')
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
                                Aktív
                                <input type="checkbox" name="status" value="1" class="form-check-input" @if($model->status ?? false) checked="" @endif>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($model->store == 1)
                        <img style="width:60px; float:right;" src="http://localhost:22200/logo-olcsokonyvek.png">
                    @elseif($model->store == 2)
                        <img style="width:60px; float:right;" src="http://localhost:22200/logo-nagyker.png">
                    @elseif($model->store == 0)
                        <img style="width:60px; float:right;" src="http://localhost:22200/logo-alomgyar.png">
                    @endif
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Cím (nem jelenik meg sehol)</label>
                            <input name="title" id="title" class="form-control @error('title') border-danger @enderror" value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Tárgy</label>
                            <input name="subject" id="subject" class="form-control @error('subject') border-danger @enderror" value="{{ !is_null(old('subject')) ? old('subject') : $model->subject ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Szekció</label>
                            <input disabled name="section" id="section" class="form-control @error('section') border-danger @enderror" value="{{ !is_null(old('section')) ? old('section') : $model->section ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Azonosító kulcs</label>
                            <input disabled name="slug" id="slug" class="form-control @error('slug') border-danger @enderror" value="{{ !is_null(old('slug')) ? old('slug') : $model->slug ?? '' }}">
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

            @if($content = \Alomgyar\Templates\Services\AdminHelperService::create()->helper($model->slug))
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title font-weight-semibold">
                            <i class="icon-help mr-2"></i>
                            Segítség
                        </h6>
                    </div>

                    <div class="card-body">
                        {!! $content !!}
                    </div>
                </div>
            @endif
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
