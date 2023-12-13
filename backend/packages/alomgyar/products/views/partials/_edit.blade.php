<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="col-form-label font-weight-bold">Cím</label>
                        <input name="title" id="title" class="form-control @error('title') border-danger @enderror"
                            value="{{ !is_null(old('title')) ? old('title') : $model->title ?? '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label font-weight-bold">Slug</label>
                    <input name="slug" id="slug"
                        class="col-md-9 form-control @error('slug') border-danger @enderror"
                        value="{{ !is_null(old('slug')) ? old('slug') : $model->slug ?? '' }}">
                    @error('slug')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="col-form-label font-weight-bold">Leírás</label>
                        <textarea name="description" id="description"
                            class="summernote form-control @error('description') border-danger @enderror">{{ !is_null(old('description')) ? old('description') : '' }}@isset($model) {{ $model->description ?? '' }} @endisset
</textarea>
                        @error('description')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @isset($model)
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="card-title font-weight-semibold">Megtekintés</span>

                    <small><a target="_blank" style="color:#e62934;"
                            href="https://alomgyar.hu/konyv/{{ $model->slug }}"><img style="width:60px;"
                                src="/logo-alomgyar.png"></a></small>
                    <small><a target="_blank" style="color:#fbc72e;"
                            href="https://olcsokonyvek.hu/konyv/{{ $model->slug }}"><img style="width:60px;"
                                src="/logo-olcsokonyvek.png"></a></small>
                    <small><a target="_blank" style="color:#4971ff;"
                            href="https://nagyker.alomgyar.hu/konyv/{{ $model->slug }}"><img style="width:60px;"
                                src="/logo-nagyker.png"></a></small>

                </div>
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="card-title font-weight-semibold">Letöltés</span>
                    @if (!empty($model->dibook_id))
                        <a href="{{ route('product.download', ['admin' => true, 'bookId' => $model->id]) }}">Letöltés
                            Dibook-ról</a>
                    @endif
                </div>



                {{-- }}
            <div class="card-body">
                <small><a target="_blank" style="color:#e62934;" href="https://alomgyar.hu/konyv/{{$model->slug}}">https://alomgyar.hu/konyv/{{$model->slug}}</a></small>
                <small><a target="_blank" style="color:#fbc72e;" href="https://olcsokonyvek.hu/konyv/{{$model->slug}}">https://olcsokonyvek.hu/konyv/{{$model->slug}}</a></small>
                <small><a target="_blank" style="color:#4971ff;" href="https://nagyker.alomgyar.hu/konyv/{{$model->slug}}">https://nagyker.alomgyar.hu/konyv/{{$model->slug}}</a></small>
            </div> --}}
            </div>

            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="card-title font-weight-semibold">Állapotkezelés</span>

                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-md-5 col-form-label font-weight-bold">Állapot</label>
                        <div class="col-md-7">
                            <select name="state" class="form-control">
                                <option value="0" @if (old('state') ?? '' == 0 || ($model->state ?? false) == 0) selected @endif>Normál</option>
                                <option value="1" @if (old('state') ?? '' == 1 || ($model->state ?? false) == 1) selected @endif>Előjegyezhető
                                </option>
                            </select>
                        </div>
                    </div>
                    @if ($model->type == 0)
                        <div class="form-group">
                            <div class="form-check form-check-right">
                                <label class="form-check-label font-weight-bold">
                                    Book24 raktárkészlet figyelése <br><small>(Csak PAM készlet [üres] vagy BOOK24 [bepipálva])</small>
                                    <input type="hidden" name="is_dependable_status" value="0">
                                    <input type="checkbox" name="is_dependable_status" value="1"
                                        class="form-check-input" @if ($model->is_dependable_status ?? false) checked="" @endif>
                                </label>
                            </div>
                            @error('is_dependable_status')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-right">
                                <label class="form-check-label font-weight-bold">
                                    Megjelent már korábban<br> <small>{{ $model->published_at ?? '-' }}</small>
                                    <input type="hidden" name="published_before" value="0">
                                    <input type="checkbox" name="published_before" value="1" class="form-check-input"
                                           @if ($model->published_before ?? false) checked="" @endif>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endisset
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <span class="card-title font-weight-semibold">Paraméterek</span>
                <div class="header-elements">
                    <div class="form-check form-check-right">
                        <label class="form-check-label">
                            Megjelenik
                            <input type="checkbox" name="status" value="1" class="form-check-input"
                                @if ($model->status ?? false) checked="" @endif>
                        </label>
                    </div>
                </div>
            </div>


            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Típus</label>
                    <div class="col-md-7">
                        <select name="type" class="form-control">
                            <option value="0" @if (old('type') ?? '' == 0 || ($model->type ?? false) == 0) selected @endif>Könyv</option>
                            <option value="1" @if (old('type') ?? '' == 1 || ($model->type ?? false) == 1) selected @endif>E-könyv</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Kiadó</label>
                    <div class="col-md-7">
                        <select id="publisher_select" name="publisher_id" class="form-control">
                            <option value="0" @if ($model->publisher_id ?? true) selected @endif>Nincs kiadó
                            </option>
                            @foreach ($publishers ?? [] as $publisher)
                                <option @if (($model->publisher_id ?? false) == $publisher->id) selected @endif
                                    value="{{ $publisher->id }}">{{ $publisher->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Jogtulaj</label>
                    <div class="col-md-7">
                        <select name="legal_owner_id" class="form-control">
                            <option value="0" @if ($model->legal_owner_id ?? true || $model->legal_owner_id == null || $model->legal_owner_id == '') selected @endif>Nincs jogtulaj
                            </option>
                            @foreach ($legalOwners ?? [] as $legalOwner)
                                <option @if (($model->legal_owner_id ?? false) === $legalOwner->id) selected @endif
                                    value="{{ $legalOwner->id }}">{{ $legalOwner->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row"
                    title="Szerzői fogyásjelentéskor a könyv eladásokból származó jutalék mértéke %-ban">
                    <label class="col-md-5 col-form-label font-weight-bold">Szerzői jutalék</label>
                    <input type="number" name="writer_commission" id="writer_commission"
                        class="col-md-7 form-control @error('writer_commission') border-danger @enderror"
                        value="{{ !is_null(old('writer_commission')) ? old('writer_commission') : $model->writer_commission ?? '' }}">
                    @error('writer_commission')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Megjelenés éve</label>
                    <input type="number" name="release_year" id="release_year"
                        class="col-md-7 form-control @error('release_year') border-danger @enderror"
                        value="{{ !is_null(old('release_year')) ? old('release_year') : $model->release_year ?? '' }}">
                    @error('release_year')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Oldalszám</label>
                    <input type="number" name="number_of_pages" id="number_of_pages"
                        class="col-md-7 form-control @error('number_of_pages') border-danger @enderror"
                        value="{{ !is_null(old('number_of_pages')) ? old('number_of_pages') : $model->number_of_pages ?? '' }}">
                    @error('number_of_pages')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">ISBN</label>
                    <input type="number" name="isbn" id="isbn"
                        class="col-md-7 form-control @error('isbn') border-danger @enderror"
                        value="{{ !is_null(old('isbn')) ? old('isbn') : $model->isbn ?? '' }}">
                    @error('isbn')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Áfa</label>
                    <div class="col-md-7">
                        <select name="tax_rate" id="tax_rate" class="form-control">
                            <option value="5" @if (old('tax_rate') ?? '' == 5 || ($model->tax_rate ?? false) == 5) selected @endif>5 %</option>
                            <option value="27" @if (old('tax_rate') ?? '' == 27 || ($model->tax_rate ?? false) == 27) selected @endif>27 %</option>
                        </select>
                    </div>
                    @error('tax_rate')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Nyelv</label>
                    <input type="text" name="language" id="language"
                        class="col-md-7 form-control @error('language') border-danger @enderror"
                        value="{{ !is_null(old('language')) ? old('language') : $model->language ?? '' }}">
                    @error('language')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Kötésmód</label>
                    <input type="text" name="book_binding_method" id="language"
                        class="col-md-7 form-control @error('book_binding_method') border-danger @enderror"
                        value="{{ !is_null(old('book_binding_method')) ? old('book_binding_method') : $model->book_binding_method ?? '' }}">
                    @error('book_binding_method')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-md-5 col-form-label font-weight-bold">Megjelenés várható időpontja
                        <br><small>(Előjegyzés esetén)</small></label>
                    @isset($model)
                        <input type="date" name="published_at" id="published_at"
                            class="col-md-7 form-control text-right @error('published_at') border-danger @enderror"
                            value="{{ !is_null(old('published_at')) ? old('published_at') : $model->formatted_published_at ?? '' }}">
                    @else
                        <input type="date" name="published_at" id="published_at"
                            class="col-md-7 form-control text-right @error('published_at') border-danger @enderror"
                            value="{{ !is_null(old('published_at')) ? old('published_at') : '' }}">
                        @endif

                        @error('published_at')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <div class="form-check form-check-right">
                            <label class="form-check-label font-weight-bold">
                                Raktárkészlet értesítő <br><small>(GPS raktár készlet < 3)</small>
                                        <input type="hidden" name="is_stock_sensitive" value="0">
                                        <input type="checkbox" name="is_stock_sensitive" value="1"
                                            class="form-check-input" @if ($model->is_stock_sensitive ?? false) checked="" @endif>
                            </label>
                        </div>
                        @error('is_stock_sensitive')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if ($model ?? false)
                    <div class="card-footer d-flex justify-content-between">
                        @if ($model->created_at)<span
                                class="text-muted">Létrehozva: {{ $model->created_at->diffForHumans() }}</span>
                        @endif
                        @if ($model->updated_at)<span
                                class="text-muted text-right">Módosítva: {{ $model->updated_at->diffForHumans() }}</span>
                        @endif
                    </div>
                @endif
            </div>


            <!-- Subcategories -->

            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="text-uppercase font-size-sm font-weight-semibold">Alkategóriák</span>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="media-list subcategory">
                        @isset($model)
                            @foreach ($model->subcategories ?? [] as $productsubcategory)
                                @if ($productsubcategory)
                                    <li class="media">
                                        <div class="mr-3 align-self-center">
                                            <i class="icon-cube3 text-success-300 top-0"></i>
                                        </div>
                                        <div class="media-body">
                                            <div class="font-weight-semibold">
                                                <select name="subcategory[]"
                                                    class="form-control select @error('subcategory') border-danger @enderror">
                                                    @foreach ($subcategories ?? [] as $item)
                                                        <option @if ($item->id == $productsubcategory->id) selected @endif
                                                            value="{{ $item->id }}">
                                                            {{ $item->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="ml-2 align-self-center">
                                            <a href="javascript:" onclick="$(this).parent().parent().remove()"
                                                class="icon icon-trash text-dark"></a>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @endisset
                    </ul>
                    <a href="javascript:" title="Alkategória hozzáadása"
                        onClick="$('.media-list.subcategory').append($('#li-tpl').html()); $('.subcategory .newselect').select2(); $('.subcategory .newselect').removeClass('newselect');"
                        class="btn btn-outline bg-success btn-icon text-success border-success border-2 rounded-round legitRipple mt-3">
                        <i class="icon-plus3"></i></a>
                </div>
            </div>

            <!-- /Subcategories -->


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
                        @if ($model ?? false)
                            @foreach ($model->author ?? [] as $productauthor)
                                @if ($productauthor)
                                    <li class="media">
                                        <div class="mr-3 align-self-center">
                                            <i class="icon-quill4 text-success-300 top-0"></i>
                                        </div>
                                        <div class="media-body">
                                            <div class="font-weight-semibold">
                                                <select name="author[]" class="form-control select-search" data-fouc>
                                                    @if ($productauthor->id)
                                                        <option value="{{ $productauthor->id }}" selected="selected">
                                                            {{ $productauthor->title }}
                                                        </option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="ml-2 align-self-center">
                                            <a href="javascript:" onclick="$(this).parent().parent().remove()"
                                                class="icon icon-trash text-dark"></a>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                    <a href="javascript:" title="Szerző hozzáadása"
                        onClick="$('.media-list.author').append($('#li-tpl-author').html()); $('.author .newselect').addClass('select-search').removeClass('newselect'); const eventSelectRestart = new Event('restartSelect2'); window.dispatchEvent(eventSelectRestart);"
                        class="btn btn-outline bg-success btn-icon text-success border-success border-2 rounded-round legitRipple mt-3">
                        <i class="icon-plus3"></i></a>
                </div>
            </div>
            <!-- /Authors -->

            <!-- Meta Tags -->
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="text-uppercase font-size-sm font-weight-semibold">SEO</span>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Meta title</label>
                            <input name="meta_title" id="meta_title"
                                class="form-control @error('meta_title') border-danger @enderror"
                                value="{{ !is_null(old('meta_title')) ? old('meta_title') : $model->meta_title ?? '' }}">
                            @error('meta_title')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Meta description</label>
                            <textarea name="meta_description" id="meta_description"
                                class="form-control @error('meta_description') border-danger @enderror">{{ !is_null(old('meta_description')) ? old('meta_description') : $model->meta_description ?? '' }}</textarea>
                            @error('meta_description')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Meta Tags -->

            @if (($model ?? false) and $model?->type == 0)
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="card-title font-weight-semibold">További funkciók</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check form-check-right">
                                <label class="form-check-label font-weight-bold">
                                    Ne frissítse automatikusan az árat
                                    <input type="hidden" name="do_not_update_price" value="0">
                                    <input type="checkbox" name="do_not_update_price" value="1" class="form-check-input"
                                           @if ($model->do_not_update_price ?? false) checked="" @endif>
                                </label>
                            </div>
                            @error('do_not_update_price')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="card-title font-weight-semibold mt-2 mb-3">Kosár beállítások</div>

                        <div class="form-group">
                            <div class="form-check form-check-right">
                                <label class="form-check-label font-weight-bold">
                                    Fizetés csak előfizetéssel<br> <small>utánvét tiltása</small>
                                    <input type="hidden" name="only_prepay" value="0">
                                    <input type="checkbox" name="only_prepay" value="1" class="form-check-input"
                                           @if ($model->only_prepay ?? false) checked="" @endif>
                                </label>
                            </div>
                            @error('only_prepay')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-right">
                                <label class="form-check-label font-weight-bold">
                                    Ingyenes szállítás
                                    <input type="hidden" name="free_delivery" value="0">
                                    <input type="checkbox" name="free_delivery" value="1" class="form-check-input"
                                           @if ($model->free_delivery ?? false) checked="" @endif>
                                </label>
                            </div>
                            @error('free_delivery')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-right">
                                <label class="form-check-label font-weight-bold">
                                    Nem rendelhető más termékkel együtt
                                    <input type="hidden" name="order_only_alone" value="0">
                                    <input type="checkbox" name="order_only_alone" value="1" class="form-check-input"
                                           @if ($model->order_only_alone ?? false) checked="" @endif>
                                </label>
                            </div>
                            @error('order_only_alone')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-right">
                                <label class="form-check-label font-weight-bold">
                                    Csak boltban vehető át
                                    <input type="hidden" name="order_only_shop" value="0">
                                    <input type="checkbox" name="order_only_shop" value="1" class="form-check-input"
                                           @if ($model->order_only_shop ?? false) checked="" @endif>
                                </label>
                            </div>
                            @error('order_only_shop')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

    </div>
    </div>
    <div class="row">

    </div>
