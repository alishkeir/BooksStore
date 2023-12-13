<div class="tab-pane fade active show" id="edit">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

                    @if (!isset($model))
                        <input type="hidden" name="password" value="{{ rand(4000, 20000) }}">
                    @endif

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">E-mail</label>
                            <input name="email" id="email"
                                class="form-control @error('email') border-danger @enderror"
                                value="{{ !is_null(old('email')) ? old('email') : $model->email ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Vezetéknév</label>
                            <input name="lastname" id="lastname"
                                class="form-control @error('lastname') border-danger @enderror"
                                value="{{ !is_null(old('lastname')) ? old('lastname') : $model->lastname ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Keresztnév</label>
                            <input name="firstname" id="firstname"
                                class="form-control @error('firstname') border-danger @enderror"
                                value="{{ !is_null(old('firstname')) ? old('firstname') : $model->firstname ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Telefon</label>
                            <input name="phone" id="phone"
                                class="form-control @error('phone') border-danger @enderror"
                                value="{{ !is_null(old('phone')) ? old('phone') : $model->phone ?? '' }}">
                        </div>
                    </div>
                    {{-- IN ORDER TO AVOID VALIDATION FAIL --}}
                    <input type="hidden" name="affiliate_status" value="0">
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
                                <input type="checkbox" name="status" value="10" class="form-check-input"
                                    @if ($model->status ?? false == 10) checked="" @endif>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input name="store" type="radio" value="0" class="form-check-input"
                                    @if (($model->store ?? false) == 0) checked="" @endif>
                                Álomgyár
                            </label>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input name="store" type="radio" value="1" class="form-check-input"
                                    @if (($model->store ?? false) == 1) checked="" @endif>
                                Olcsókönyvek
                            </label>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input name="store" type="radio" value="2" class="form-check-input"
                                    @if (($model->store ?? false) == 2) checked="" @endif>
                                Nagyker
                            </label>
                        </div>
                    </div>
                </div>

                @if ($model ?? false)
                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                        <span class="text-muted text-right">Módosítva: {{ $model->updated_at->diffForHumans() }}</span>
                    </div>
                @endif
            </div>

            @if (($model ?? false) && $model->store == 2)
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title font-weight-semibold">
                            <i class="icon-cabinet mr-2"></i>
                            Nagyker egyéni kedvezmények
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Álomgyár könyvekre kedvezmény (%)</label>
                                <input type="number" name="personal_discount_alomgyar" id="personal_discount_alomgyar"
                                    class="form-control @error('personal_discount_alomgyar') border-danger @enderror"
                                    value="{{ !is_null(old('personal_discount_alomgyar')) ? old('personal_discount_alomgyar') : $model->personal_discount_alomgyar ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Többi könyvre kedvezmény (%)</label>
                                <input type="number" name="personal_discount_all" id="personal_discount_all"
                                    class="form-control @error('personal_discount_all') border-danger @enderror"
                                    value="{{ !is_null(old('personal_discount_all')) ? old('personal_discount_all') : $model->personal_discount_all ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    Vásárlás után automatikus feliratkozás a szerzőre: <strong>
                        @if ($model->author_follow_up ?? false)
                            Igen
                        @else
                            Nem
                        @endif
                    </strong>
                </div>
                <div class="card-header header-elements-inline">

                    <h5>Követett szerzők</h5>
                </div>
                <div class="card-body">
                    @forelse ($model->authors ?? [] as $author)
                        <a href="/gephaz/authors/{{ $author['id'] }}/edit"
                            class="badge badge-primary">{{ $author['title'] }}</a>

                    @empty
                        <div class="text-grey">Nincs követett szerző</div>
                    @endforelse
                </div>
            </div>
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5>Megvásárolt e-könyvek</h5>
                </div>
                <div class="card-body">
                    @forelse ($model->ebooks ?? [] as $ebook)
                        <a href="/gephaz/products/{{ $ebook['id'] }}/edit"
                            class="badge badge-success">{{ $ebook['title'] }}</a>

                    @empty
                        <div class="text-grey">Nincs e-könyv</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
