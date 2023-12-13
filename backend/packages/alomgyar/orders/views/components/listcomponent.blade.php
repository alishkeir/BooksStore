<div>
    <div class="d-md-flex align-items-md-start">

        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left border-0 shadow-0 sidebar-expand-md"
            style="width:13rem">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Filter -->
                <div class="card">
                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
                        <li class="nav-item"><a href="#tab-spec" class="nav-link legitRipple active show"
                                data-toggle="tab">Szűrő</a></li>
                    </ul>

                    <div class="tab-content border-top-0 rounded-top-0 mb-0">

                        <div class="tab-pane fade active show" id="tab-spec">

                            <div class="card-body">
                                <form action="#">
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <input wire:model.debounce.300ms="s" type="search" class="form-control"
                                            placeholder="Keresés">
                                        <div class="form-control-feedback">
                                            <i class="icon-search4 text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select wire:model="filters.status" class="form-control select select2"
                                            name="status" onChange="handleSelect(this)">
                                            <option selected value="">Rendelés állapota</option>
                                            <option value="1">Megrendelve</option>
                                            <option value="2">Feldolgozás alatt</option>
                                            <option value="3">Összekészítve</option>
                                            <option value="4">Szállítás alatt</option>
                                            <option value="5">Átvehető</option>
                                            <option value="6">Teljesítve</option>
                                            <option value="7">Visszaküldött</option>
                                            <option value="8">Törölt</option>
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-database-check text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select wire:model="filters.payment_status" class="form-control select select2"
                                            name="payment_status" onChange="handleSelect(this)">
                                            <option selected value="">Fizetési állapot</option>
                                            <option value="1">Nem fizetett</option>
                                            <option value="3">Fizetett</option>
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-database-check text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select wire:model="filters.payment_method" class="form-control  select select2"
                                            name="payment_method" onChange="handleSelect(this)">
                                            <option selected value="">Fizetési mód</option>
                                            @foreach ($payment as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-cash2 text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select wire:model="filters.shipping_method"
                                            class="form-control  select select2" name="shipping_method"
                                            onChange="handleSelect(this)">
                                            <option selected value="">Szállítási mód</option>
                                            @foreach ($shipping as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-bag text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select wire:model="filters.shop" class="form-control select select2"
                                            name="shop" onChange="handleSelect(this)">
                                            <option selected value="">Boltok</option>
                                            @foreach ($shops ?? [] as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-location4 text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <input type="datetime-local" wire:model="filters.from" class="form-control"
                                                value="{{ $filters->from ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <input type="datetime-local" wire:model="filters.to" class="form-control"
                                                value="{{ $filters->to ?? '' }}">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="form-check-label">
                                            <div><span><input wire:model="filters.is_shop" type="checkbox"></span> Csak
                                                bolti eladás</div>
                                        </label><br>
                                        <label class="form-check-label">
                                            <div><span><input wire:model="filters.is_webshop" type="checkbox"></span>
                                                Csak webshop</div>
                                        </label><br><br>
                                        <label class="form-check-label">
                                            <div><span><input wire:model="filters.only_current" type="checkbox"></span>
                                                2 hónapja változott</div>
                                        </label><br><br>
                                        <label class="form-check-label">
                                            <div><span><input wire:model="filters.delivery_ok" type="checkbox"></span>
                                                Teljesíthető</div>
                                        </label><br>
                                        {{-- }}
                                        <label class="form-check-label">
                                            <div ><span><input wire:model="filters.delivery_almost" type="checkbox" ></span> Teljesíthető (teendővel)</div>
                                        </label><br>
                                        <label class="form-check-label">
                                            <div ><span><input wire:model="filters.delivery_no" type="checkbox" ></span> Nem teljesíthető</div>
                                        </label><br><br> --}}
                                        <label class="form-check-label">
                                            <div><span><input wire:model="filters.only_selection"
                                                        type="checkbox"></span> Csak a kijelöltek</div>
                                        </label><br>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- /filter -->
                <div class="card card-body border-top-1 border-top-primary">
                    <div class="text-center">
                        <h6 class="mb-1 font-weight-semibold">Vonalkód</h6>
                    </div>
                    <div class="row row-tile no-gutters">
                        <input wire:model="barCodeInput" class="form-control">

                    </div>
                </div>
                {{-- }}
                <div class="card card-body border-top-1 border-top-primary">
                    <div class="text-center">
                        <h6 class="mb-1 font-weight-semibold">Rendelések</h6>
                    </div>
                    <div class="row row-tile no-gutters">
                        <div class="col-6">
                            <a href="{{route('products.export', $filters)}}" class="btn btn-outline-success  btn-block btn-float legitRipple">
                                Export
                            </a>
                        </div>

                        <div class="col-6">
                            <a href="{{route('products.import')}}" class="btn btn-outline-warning btn-block btn-float legitRipple">
                                Import
                            </a>
                        </div>
                    </div>
                </div> --}}
            </div>
            <!-- /sidebar content -->

        </div>


        <div class="flex-fill">


            <div class="row">
                <div class="col-md-2">
                    <div class="card card-body py-3" style="min-height:92px;">
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
                    @if ($selection ?? false)
                        <a wire:click="downloadExcel()"
                            title="Töltse le az összes kiválasztott rendelést Excel fájlként"
                            onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                            class="btn btn-info btn-sm text-white text-left py-1"><i class="icon-download mr-1"></i>
                            Excel</a>
                    @endif
                </div>

                @if ($selection ?? false)

                    <div class="col-md-10">
                        <div class="card" style="min-height:92px;">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="table-border-double">
                                        @foreach ($selectionStatuses as $status => $count)
                                            <th>{{ $count }}</strong> '{{ $statusName[$status] ?? 'N/A' }}'</th>
                                        @endforeach
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($selectionStatuses as $status => $count)
                                            <td>
                                                @if ($status < 6)
                                                    <strong>Állapot állítás</strong><br>
                                                    <a wire:click="setOrderStatuses({{ $status }}, {{ $status + 1 }})"
                                                        title="{{ $count }} db megrendelés állapotának átállítás erre: {{ $statusName[$status + 1] ?? 'N/A' }}"
                                                        onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                        class="btn btn-success btn-sm text-white text-left py-1"><i
                                                            class="icon-arrow-right8 mr-1"></i>
                                                        {{ $statusName[$status + 1] ?? 'N/A' }}</a>
                                                @endif
                                                <br><a wire:click="setOrderStatuses({{ $status }}, 8)"
                                                    title="{{ $count }} db megrendelés törlése"
                                                    onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                    class="btn btn-danger btn-sm text-white text-left py-1 mt-2"><i
                                                        class="icon-arrow-right8 mr-1"></i> Törlés</a>
                                                <br><a wire:click="setOrderPaid({{ $status }})"
                                                    title="Fizetettre állítás"
                                                    onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                    class="btn btn-warning btn-sm text-white text-left py-1 mt-2"><i
                                                        class="icon-arrow-right8 mr-1"></i> Fizetettre</a>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="table-border-double">
                                        <th>Házhozszállítás</th>
                                        <th>Csomagpont</th>
                                        <th>Boltokban</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a wire:click="downloadShippingExcel('sprinter', 'home')"
                                                title="Sprinter excel letöltés, a kijelöltek közül, ahol a szállítási mód Házhozszállítás"
                                                onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                class="btn btn-info btn-sm text-white text-left py-1"><i
                                                    class="icon-download mr-1"></i> Sprinter</a>
                                            <a wire:click="downloadShippingExcel('dpd', 'dpd')"
                                                title="DPD excel letöltés, a kijelöltek közül, ahol a szállítási mód Házhozszállítás"
                                                onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                class="btn btn-info btn-sm text-white text-left py-1"><i
                                                    class="icon-download mr-1"></i> DPD</a>
                                            <a wire:click="downloadShippingExcel('sameday', 'sameday')"
                                                title="SameDay excel letöltés, a kijelöltek közül, ahol a szállítási mód Házhozszállítás"
                                                onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                class="btn btn-info btn-sm text-white text-left py-1"><i
                                                    class="icon-download mr-1"></i> SameDay</a>
                                        </td>
                                        <td>
                                            <a wire:click="downloadShippingExcel('sprinter', 'box')"
                                                title="Sprinter excel letöltés, a kijelöltek közül, ahol a szállítási mód Csomagpont - Posta"
                                                onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                class="btn btn-info btn-sm text-white text-left py-1"><i
                                                    class="icon-download mr-1"></i> Sprinter</a>
                                            <a wire:click="downloadShippingExcel('easybox', 'box')"
                                                title="Easybox excel letöltés, a kijelöltek közül, ahol a szállítási mód Csomagpont - Easybox"
                                                onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                                class="btn btn-info btn-sm text-white text-left py-1"><i
                                                    class="icon-download mr-1"></i> Easybox</a>
                                            <a wire:click="downloadShippingExcel('packeta', 'box')"
                                               title="Packeta excel letöltés, a kijelöltek közül, ahol a szállítási mód Csomagpont - Easybox"
                                               onClick="$(this).find('i').addClass('icon-spinner4 spinner')"
                                               class="btn btn-info btn-sm text-white text-left py-1"><i
                                                    class="icon-download mr-1"></i> Packeta</a>
                                        <td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                @endif
            </div>

            <div class="card">
                <div class="card-body">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <a href="javascript:" role="button" class="text-default">
                                        <input type="checkbox" wire:click="selectAll" wire:model="allSelected">
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:" wire:click.prevent="sortBy('id')" role="button"
                                        class="text-default">
                                        Rendelésszám
                                        @include('admin::partials._sort-icons', ['field' => 'orders.id'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:" wire:click.prevent="sortBy('total_amount')" role="button"
                                        class="text-default">
                                        Ár
                                        @include('admin::partials._sort-icons', [
                                            'field' => 'orders.total_amount',
                                        ])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:" wire:click.prevent="sortBy('shipping_fee')" role="button"
                                        class="text-default">
                                        Szállítási mód/díj
                                        @include('admin::partials._sort-icons', [
                                            'field' => 'shipping_fee',
                                        ])
                                    </a>
                                </th>
                                <th> Fizetési mód/díj</th>
                                <th>Ügyfél</th>
                                <th>
                                    <a href="javascript:" wire:click.prevent="sortBy('created_at')" role="button"
                                        class="text-default">
                                        Dátum
                                        @include('admin::partials._sort-icons', [
                                            'field' => 'orders.created_at',
                                        ])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:" wire:click.prevent="sortBy('status')" role="button"
                                        class="text-default">
                                        Állapot
                                        @include('admin::partials._sort-icons', [
                                            'field' => 'orders.status',
                                        ])
                                    </a>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Rendelésszám</th>
                                <th>Ár</th>
                                <th>Szállítási mód/díj</th>
                                <th>Fizetési mód/díj</th>
                                <th>Ügyfél</th>
                                <th>Dátum</th>
                                <th>Állapot</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @forelse($model as $order)
                                <tr
                                    style="border-left:3px solid @if ($order->store == 0) #e62934; @elseif($order->store == 1) #fbc72e @elseif($order->store == 2) #4971ff;  @else  #000; @endif">
                                    <td><input value="{{ $order->status }}"
                                            wire:model="selection.{{ $order->id }}" type="checkbox"></td>

                                    <td
                                        @if ($order->has_ebook) title="Tartalmaz e-bookot" class="text-success" @endif>
                                        {{ $order->order_number }}
                                    </td>
                                    <td>{{ $order->total_amount }} Ft
                                        <br><small>{{ $order->total_quantity }} db</small>
                                    </td>
                                    <td>
                                        <strong style="white-space: nowrap;">{{ $order->shippingMethod?->name }}
                                        </strong>
                                        <br><small>{{ $order->shipping_fee }} Ft</small>
                                    </td>
                                    <td>
                                        <strong
                                            style="white-space: nowrap;">{{ $order->paymentMethod?->name }}</strong>
                                        <br><small>{{ $order->payment_fee ?? 'N/A' }} Ft</small>
                                    </td>
                                    <td>
                                        <strong>{{ $order->billingAddress?->last_name }}
                                            {{ $order->billingAddress?->first_name }}</strong>
                                        <br>{{ $order->billingAddress?->city }}
                                    </td>
                                    <td>
                                        <strong>{{ substr($order->created_at, 0, 10) }}</strong>
                                        <br>{{ substr($order->created_at, 10, 9) }}
                                    </td>
                                    <td>
                                        {!! $order->status_html !!}
                                        <small>{!! $order->payment_status_html !!}</small>
                                    </td>
                                    <td class="text-right px-0" style="max-width:125px;">
                                        <div class="list-icons">
                                            @if (!empty($order->attachments))
                                                <div class="btn-group ml-2">
                                                    <button type="button"
                                                        class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        @if ($order->invoice_url != '')
                                                            <i class="icon icon-file-check text-success"></i>
                                                        @endif
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="top-start"
                                                        style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">

                                                        @foreach ($order->attachments as $attachment)
                                                            <a href="{{ route('orders.invoice.get', ['id' => $attachment]) }}"
                                                                target="_blank"
                                                                class="dropdown-item @if ($attachment == $order->invoice_url) bg-success @endif"><strong>{{ $attachment }}</strong></a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            <a target="blank_" href="/gephaz/orders/{{ $order->id }}"
                                                class="btn alpha-primary text-primary-800 btn-icon ml-0 legitRipple"><i
                                                    class="icon-file-eye2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="p-4 text-center">
                                        <h5>Nincs megjeleníthető rendelés</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @include('admin::partials._pagination')
                </div>
            </div>
        </div>

    </div>
