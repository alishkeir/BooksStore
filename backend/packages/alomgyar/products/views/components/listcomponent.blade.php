<div class="d-md-flex align-items-md-start">
    <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left border-0 shadow-0 sidebar-expand-md"
        style="width:13rem">

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Filter -->
            <div class="card">
                <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
                    <li class="nav-item"><a wire:click="setTab('tab-1')" href="#tab-desc"
                            class="nav-link legitRipple @if ($filters['tab'] == 'tab-1') active show @endif"
                            data-toggle="tab">Webshop</a> </li>
                    <li class="nav-item"><a wire:click="setTab('tab-2')" href="#tab-spec"
                            class="nav-link legitRipple @if ($filters['tab'] == 'tab-2') active show @endif"
                            data-toggle="tab">Ügyvitel</a> </li>
                </ul>

                <div class="tab-content border-top-0 rounded-top-0 mb-0">
                    <div class="tab-pane fade @if ($filters['tab'] == 'tab-1') active show @endif" id="tab-desc">
                        <div class="card-body">
                            <form action="#">
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input wire:model.debounce.500ms="filters.search" type="search"
                                        class="form-control" placeholder="Keresés">
                                    <div class="form-control-feedback">
                                        <i class="icon-search4 text-muted"></i>
                                    </div>
                                </div>
                                {{-- }}
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select wire:model="filters.category" class="form-control  select select2-cat"
                                            name="category" onChange="handleSelect(this)">
                                        <option></option>
                                        @foreach ($categories ?? [] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-cube4 text-muted"></i>
                                    </div>
                                </div> --}}
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select wire:model="filters.subcategory" class="form-control select select2-subcat"
                                        name="subcategory" onChange="handleSelect(this)">
                                        <option></option>
                                        @foreach ($subcategories ?? [] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-cube3 text-muted"></i>
                                    </div>
                                </div>
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select class="form-control select-search" data-fouc>
                                        @if ($filters['author'])
                                            <option value="{{ $this->author->id }}" selected="selected">
                                                {{ $this->author->title }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-quill4 text-muted"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.only_ebook" type="checkbox"></span> Csak
                                            e-könyvek
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.only_book" type="checkbox"></span> Csak
                                            könyvek
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.cart_price" type="checkbox"></span> Csak
                                            kosár árral
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.active" type="checkbox"></span> Csak
                                            látható
                                        </div>
                                    </label><br><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.stock" type="radio" name="stockstatus"
                                                    value="0"></span> Minden
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.stock" type="radio" name="stockstatus"
                                                    value="in"></span> Raktáron
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.stock" type="radio" name="stockstatus"
                                                    value="no"></span> Nincs raktáron
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.stock" type="radio" name="stockstatus"
                                                    value="low"></span> Alacsony GPS készlet
                                        </div>
                                    </label><br><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.pre" type="checkbox" name="pre"
                                                    value="1"></span> Előjegyezhető
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.normal" type="checkbox" name="pre"
                                                    value="1"></span> Nem Előjegyezhető
                                        </div>
                                    </label><br><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.source" type="radio" name="source"
                                                    value="0"></span> Minden
                                        </div>
                                    </label>


                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.source" type="radio" name="source"
                                                    value="dibook"></span> Dibook
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.source" type="radio" name="source"
                                                    value="kiajanlo"></span> Kiadói feltöltések
                                        </div>
                                    </label>
                                    <br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.b24_import" type="checkbox"
                                                    name="b24_import" value="1"></span> B24 frissen importáltak
                                        </div>
                                    </label><br><br>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.source" type="checkbox" name="source"
                                                          value="kiajanlo"></span> Kiadói feltöltések
                                        </div>
                                    </label>
                                    <label class="form-check-label">
                                        <div><span><input wire:model="filters.only_selection" type="checkbox"></span>
                                            Csak a kijelöltek</div>
                                    </label><br>
                                </div>

                            </form>
                        </div>
                        <div class="card-body border-top-1 border-top-primary">
                            <div class="text-center">
                                <h6 class="mb-1 font-weight-semibold">Kedvezmény</h6>
                            </div>
                            Min:
                            <input wire:model="filters.discount_from" type="number" min="0" max="100"
                                value="0" class="form-control"
                                style="width:70px;display:inline-block; margin-left:20px;">
                            <br>Max:
                            <input wire:model="filters.discount_to" type="number" min="0" max="100"
                                value="100"
                                class="form-control"style="width:70px;display:inline-block; margin-left:20px;">
                            {{-- <input type="text" class="form-control ion-height-helper ion" id="ion-basic" data-fouc> --}}

                        </div>
                    </div>

                    <div class="tab-pane fade @if ($filters['tab'] == 'tab-2') active show @endif" id="tab-spec">
                        <div class="card-body">
                            <form action="#">
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input wire:model="s" type="search" class="form-control" placeholder="Keresés">
                                    <div class="form-control-feedback">
                                        <i class="icon-search4 text-muted"></i>
                                    </div>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select wire:model="filters.warehouse"
                                        class="form-control select select2-warehouse" name="warehouse"
                                        onChange="handleSelect(this)">
                                        <option></option>
                                        @foreach ($warehouses ?? [] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-cube3 text-muted"></i>
                                    </div>
                                </div>
                                                                <div class="form-group form-group-feedback form-group-feedback-left">
                                                                    <select class="form-control select-search-supplier" data-fouc
                                                                            data-placeholder="Összes beszállító">
                                                                        @if ($filters['supplier'])
                                                                            <option value="{{ $this->supplier->id }}"
                                                                                    selected="selected">{{ $this->supplier->title }}
                                                                            </option>
                                                                        @endif
                                                                    </select>
                                                                    <div class="form-control-feedback">
                                                                        <i class="icon-truck text-muted"></i>
                                                                    </div>
                                                                </div>
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select class="form-control select select-search-publisher" name="publisher">
                                        <option></option>
                                        @foreach ($publishers ?? [] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-database-check text-muted"></i>
                                    </div>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select wire:model="filters.tax_rate" class="form-control select select2-taxrate"
                                        name="tax_rate" onChange="handleSelect(this)">
                                        <option></option>
                                        <option value="5">5%</option>
                                        <option value="27">27%</option>
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-cube3 text-muted"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>


            <!-- /filter -->

            <div class="card card-body border-top-1 border-top-primary">
                <div class="text-center">
                    <h6 class="mb-1 font-weight-semibold">Árazó</h6>
                    <small>A találati listából</small>
                </div>
                <div class="row row-tile no-gutters">
                    {{-- <div class="col-6"> --}}
                    <div class="col">
                        <a href="{{ route('products.export', $filters) }}"
                            class="btn btn-outline-success  btn-block btn-float legitRipple">
                            Export
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('products.import') }}"
                            class="btn btn-outline-warning btn-block btn-float legitRipple">
                            Import
                        </a>
                    </div>
                </div>

            </div>

            <!-- book24 manual sync button-->

            <div class="card card-body border-top-1 border-top-primary">
                @if ($lastDownloaded)
                    <div class="alert alert-info show p-1" role="alert">
                        <span>legutoljára {{ $lastDownloaded }} frissítve</span>
                    </div>
                @endif
                <div class="text-center">
                    <button wire:click="manualDownloadBook24" type="button"
                        class="btn btn-sm btn-primary mb-1 w-100">Book24 lista frissítése</button>
                </div>
                @if ($isDownloadSuccess)
                    <div class="alert alert-success alert-dismissible show p-1" role="alert">
                        <span>Book24 lista sikeresen frissítve!</span>
                        <button wire:click="$set('isDownloadSuccess', false)" type="button" class="close px-1"
                            data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{-- <div class="d-flex justify-content-between">
                        <span>Book24 lista sikeresen frissítve!</span>
                        <button class="btn btn-outline px-1 py-0" wire:click="$set('isDownloadSuccess', false)">&times;</button>
                    </div> --}}
                @elseif($showDownloadWaitingMessage)
                    <div class="alert alert-warning alert-dismissible show p-1" role="alert">
                        <span>Frissítés {{ $hoursPerDownload }} óránként lehetséges</span>
                        <button wire:click="$set('showDownloadWaitingMessage', false)" type="button"
                            class="close px-1">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="text-center">
                    <button wire:click="manualSyncBook24" type="button"
                        class="btn btn-sm btn-primary mb-1 w-100">Book24
                        szinkronizálás</button>
                </div>
                @if ($isSyncSuccess)
                    <div class="alert alert-success alert-dismissible show p-1" role="alert">
                        <span>Sikeres manuális szinkronizálás</span>
                        <button wire:click="$set('isSyncSuccess', false)" type="button" class="close px-1">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif($showSyncWaitingMessage)
                    <div class="alert alert-warning alert-dismissible show p-1" role="alert">
                        <span>Még {{ $syncWaitingTimeInMinutes }} perc van hátra a folyamatból</span>
                        <button wire:click="$set('showSyncWaitingMessage', false)" type="button" class="close px-1">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>

        </div>
        <!-- /sidebar content -->

    </div>

    <div class="flex-fill">

        <div class="row">
            <div class="col-md-2">
                <div class="card card-body py-2">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-check icon-2x opacity-75"></i>
                        </div>
                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ count($selection) }}</h3>
                            <span class="text-uppercase font-size-xs">Kijelölés</span>
                        </div>
                    </div>
                </div>



            </div>

            <div class="col-md-5">
                <div class="card card-body border-top-1 border-top-primary p-0 pt-1 ">
                    <div>
                        <h6 class="mb-1 ml-2">Kijelölés isbn kódok alapján</h6>
                    </div>
                    <div class="row row-tile no-gutters">
                        <input wire:model="isbnCodeInput" class="form-control">

                    </div>
                </div>
            </div>
            @if ($selection ?? false)
                <div class="col-md-2">
                    <div class="card card-body">
                        <a wire:click="setProductStatuses({{ 0 }})"
                            onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                            class="btn btn-sm btn-danger text-white legitRipple"><i></i> Kijelöltek inaktiválása</a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card card-body">
                        <a wire:click="setProductStatuses({{ 1 }})"
                            onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                            class="btn btn-sm btn-success text-white legitRipple"><i></i> Kijelöltek aktiválása</a>
                    </div>
                </div>

                @can('products.destroy')
                    <div class="col-md-3">
                        <div class="card card-body">
                            <a wire:click="deleteSelected()"
                                onclick="confirm('Biztos, hogy törölni akarod a kiválasztottakat?') || event.stopImmediatePropagation()"
                                class="btn btn-sm btn-danger text-white legitRipple"><i></i> Kijelöltek végleges
                                törlése</a>
                        </div>
                    </div>
                @endcan
            @endif
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="actual">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="javascript:" wire:click.prevent="sortBy('id')" role="button"
                                            class="text-default">
                                            <input type="checkbox" wire:click="selectAll" wire:model="allSelected">
                                        </a>
                                    </th>
                                    <th>
                                        <a href="javascript:" wire:click.prevent="sortBy('title')" role="button"
                                            class="text-default">
                                            Név / Szerző
                                            @include('admin::partials._sort-icons', ['field' => 'title'])
                                        </a>
                                    </th>
                                    <th>
                                        <a href="javascript:" wire:click.prevent="sortBy('isbn')" role="button"
                                            class="text-default">
                                            Isbn / Alkategória
                                            @include('admin::partials._sort-icons', ['field' => 'isbn'])
                                        </a>
                                    </th>
                                    <th>
                                        <a href="javascript:" role="button" class="text-default">
                                            Akciós ár
                                        </a>
                                    </th>
                                    <th>
                                        <a href="javascript:" wire:click.prevent="sortBy('status')" role="button"
                                            class="text-default">
                                            Info
                                            @include('admin::partials._sort-icons', ['field' => 'status'])
                                        </a>
                                    </th>
                                    {{--                                <th> --}}
                                    {{--                                    <a href="javascript:" role="button" class="text-default"> --}}
                                    {{--                                        Beszállítók --}}
                                    {{--                                    </a> --}}
                                    {{--                                </th> --}}
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Név / Szerző</th>
                                    <th>Isbn / Alkategória</th>
                                    <th>Akciós ár</th>
                                    <th>Info</th>
                                    {{--                                <th>Beszállítók</th> --}}
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @forelse($model as $product)
                                    <tr @if ($product->type == 1) class="border-left border-success" @endif>
                                        <td style="width:30px;" title="{{ $product->id }}"><input
                                                wire:model="selection.{{ $product->id }}" type="checkbox"> </td>
                                        <td style="max-width:230px;">
                                            <strong>{{ $product->title }}</strong>

                                            <p>
                                                <span class="badge badge-light">{{ $product->authors }}</span>
                                            </p>
                                        </td>
                                        <td>
                                            {{ $product->isbn }}
                                            <br>
                                            <p>
                                                @forelse($product->subcategories ?? [] as $sub)
                                                <span class="badge badge-light">{{ $sub->title }}</span> @empty
                                                    <span class="badge badge-warning">Nincs alkategória</span>
                                                @endforelse
                                            </p>
                                        </td>
                                        <td style="white-space:nowrap;">



                                            @if ($product->store_0 ?? false)
                                                <div style="color:#e62934; display:inline-block">
                                                    {{ $product->everyPrices?->where('store', 0)?->first()?->price_sale ?? '!' }}
                                                    <br>
                                                    <small>{{ $product->everyPrices?->where('store', 0)?->first()?->discount_percent ?? '0' }}%</small>
                                                </div>
                                            @endif
                                            @if ($product->type == 0)
                                                @if ($product->store_1 ?? false)
                                                    <div style="color:#fbc72e; display:inline-block">
                                                        {{ $product->everyPrices?->where('store', 1)?->first()?->price_sale ?? '!' }}
                                                        <br>
                                                        <small>{{ $product->everyPrices?->where('store', 1)?->first()?->discount_percent ?? '0' }}%</small>
                                                    </div>
                                                @endif
                                                @if ($product->store_2 ?? false)
                                                    <div style="color:#4971ff; display:inline-block">
                                                        {{ $product->everyPrices?->where('store', 2)?->first()?->price_sale ?? '!' }}
                                                        <br>
                                                        <small>{{ $product->everyPrices?->where('store', 2)?->first()?->discount_percent ?? '0' }}%</small>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->status == 0)
                                                <p class="text-center btn-danger d-block">INAKTÍV</p>
                                            @else
                                                <span title="Eladások száma Álomgyár"
                                                    class="badge badge-flat badge-pill border-dark text-dark"
                                                    style="color:#e62934 !important; border-color:#e62934 !important">
                                                    <small style="font-size:10px;" class="icon icon-cash2"></small>
                                                    {{ $product->orders_count_0 ?? 0 }}
                                                </span>
                                                <span title="Eladások száma Olcsókönyvek"
                                                    class="badge badge-flat badge-pill border-dark text-dark"
                                                    style="color:#fbc72e !important; border-color:#fbc72e !important">
                                                    <small style="font-size:10px;" class="icon icon-cash2"></small>
                                                    {{ $product->orders_count_1 ?? 0 }}
                                                </span>
                                                <span title="Raktárkészlet"
                                                    class="badge badge-flat badge-pill border-info text-info-600">
                                                    {{ $product->stock }} db
                                                </span>
                                                @if ($filters['warehouse'])
                                                    <span title="Ebben a raktárban"
                                                        class="badge badge-flat badge-pill border-primary text-primary-600">
                                                        {{ $product->getStockAttribute($filters['warehouse']) }} db
                                                    </span>
                                                @endif
                                                @if ($product->state == 1)
                                                    <span title="Előjegyezhető"
                                                        class="badge badge-flat badge-pill border-warning text-warning-600">
                                                        <small style="font-size:10px;">ELŐ</small>
                                                    </span>
                                                @endif
                                                <br>
                                                <p>
                                                    @if ($product->publisher_id)
                                                        <span class="badge badge-light">
                                                            {{-- {{ $publishers->where('id', $product->publisher_id)->first()->title ?? '' }} --}}
                                                            {{ $product->publisher?->title ?? '' }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning">Nincs kiadó</span>
                                                    @endif
                                                </p>
                                            @endif
                                        </td>
                                        {{--                                    <td> --}}
                                        {{--                                        <p> --}}
                                        {{--                                            @if ($product->type == 0) --}}
                                        {{--                                                @if ($product->suppliers) --}}
                                        {{--                                                    <span class="badge badge-light">{{ $product->suppliers }}</span> --}}
                                        {{--                                                @else --}}
                                        {{--                                                    <span class="badge badge-warning">Nincs beszállító</span> --}}
                                        {{--                                                @endif --}}
                                        {{--                                            @else --}}
                                        {{--                                            @if ($product->dibook_sync == 1) --}}
                                        {{--                                                <i title="Szinkron bekapcsolva" class="icon icon-check"></i> --}}
                                        {{--                                            @endif --}}
                                        {{--                                             {{$product->dibook_id}} --}}
                                        {{--                                            @endif --}}
                                        {{--                                        </p> --}}
                                        {{--                                    </td> --}}
                                        <td class="text-right" style="max-width:40px;">
                                            <div class="list-icons">
                                                <div class="btn-group ml-2">
                                                    <button type="button"
                                                        class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="icon-menu7"></i></button>

                                                    <div class="dropdown-menu" x-placement="top-start"
                                                        style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        @can('products.storing')
                                                            <a target="blank_"
                                                                href="{{ route('products.edit', ['product' => $product]) }}"
                                                                class="dropdown-item"><i class="icon icon-gear"></i> Könyv
                                                                szerkesztése</a>
                                                        @endcan
                                                        <a target="blank_"
                                                            href="{{ route('warehouses.stock-in', ['productId' => $product->id]) }}"
                                                            class="dropdown-item">Bevételezés</a>
                                                        <a target="blank_"
                                                            href="{{ route('products.edit', ['product' => $product]) }}#stock"
                                                            class="dropdown-item">Raktárkészlet állapot</a>

                                                        @if ($product->store_0 ?? false)
                                                            <a target="blank_"
                                                                href="https://alomgyar.hu/konyv/{{ $product->slug }}"
                                                                style="color:#e62934;" class="dropdown-item">Könyv
                                                                megtekintése</a>
                                                        @endif
                                                        @if ($product->store_1 ?? false)
                                                            <a target="blank_"
                                                                href="https://olcsokonyvek.hu/konyv/{{ $product->slug }}"
                                                                style="color:#fbc72e;" class="dropdown-item">Könyv
                                                                megtekintése</a>
                                                        @endif
                                                        @if ($product->store_2 ?? false)
                                                            <a target="blank_"
                                                                href="https://nagyker.alomgyar.hu/konyv/{{ $product->slug }}"
                                                                style="color:#4971ff;" class="dropdown-item">Könyv
                                                                megtekintése</a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Nincs találat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('admin::partials._pagination')
            </div>
        </div>

        <style>
            td {

                box-sizing: content-box;
            }
        </style>
    </div>
    <div wire:loading wire:target="manualSyncBook24, manualDownloadBook24"
        style="z-index: 99999; background: rgba(255,255,255,0.5); justify-content: center; align-items:center; position: fixed; inset: 0">
        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <h3>
                Book24 szinkronizálás folyamatban...
            </h3>
        </div>
    </div>
</div>
