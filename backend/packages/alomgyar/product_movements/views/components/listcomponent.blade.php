<div class="d-md-flex align-items-md-start">
    @if ($productId === 0)
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left border-0 shadow-0 sidebar-expand-md"
            style="width:13rem">
            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Filter -->
                <div class="card border-top-1 border-top-info">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-size-sm font-weight-semibold">Szűrő</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="#">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input wire:model.debounce.500ms="s" type="search" class="form-control"
                                    placeholder="Keresés">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 text-muted"></i>
                                </div>
                            </div>
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <select class="form-control select select2" wire:model="byType">
                                    <option selected value="">Típus</option>
                                    <option value="0">Raktárak közötti</option>
                                    <option value="1">Eladás (webshop)</option>
                                    <option value="2">Eladás (bolt)</option>
                                    <option value="3">Beszerzés</option>
                                    <option value="4">Leltár</option>
                                    <option value="5">Egyéb</option>
                                    <option value="6">Kereskedői</option>
                                </select>
                                <div class="form-control-feedback">
                                    <i class="icon-database-check text-muted"></i>
                                </div>
                            </div>
                            <div wire:ignore class="form-group form-group-feedback form-group-feedback-left">
                                <select class="form-control select select2 byWarehouse" wire:model="byWarehouse">
                                    <option value="">Válassz egy raktárt</option>
                                    @foreach ($this->warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback">
                                    <i class="icon-office text-muted"></i>
                                </div>
                            </div>
                            <div wire:ignore class="form-group form-group-feedback form-group-feedback-left">
                                <select class="form-control select select2 byShop" wire:model="byShop">
                                    <option value="">Válassz egy boltot</option>
                                    @foreach ($this->shops as $shop)
                                        <option value="{{ $shop->id }}">{{ $shop->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback">
                                    <i class="icon-store2 text-muted"></i>
                                </div>
                            </div>

                            <h6>Időintervallum:</h6>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="datetime-local" wire:model="from" class="form-control"
                                        value="{{ $from ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="datetime-local" wire:model="to" class="form-control"
                                        value="{{ $to ?? '' }}">
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- }}
                <div class="card-body">
                    Min. mennyiség
                    <input type="number" min="0" wire:model.debounce.500ms="minQuantity" class="form-control">

                </div> --}}
                </div>

            </div>
            <!-- /sidebar content -->
        </div>
    @endif
    <div class="flex-fill overflow-auto">
        <div class="card">
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="actual">

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>Azonosító</th>
                                    {{-- <th>Menny.</th> --}}
                                    <th>Típus</th>
                                    {{--                                <th class="text-right">Mennyiség</th> --}}
                                    <th>Forrás</th>
                                    <th>Cél</th>
                                    <th>Létrehozás</th>
                                    <th>Megjegyzés</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th># </th>
                                    <th>Azonosító</th>
                                    {{-- <th>Menny.</th> --}}
                                    <th>Típus</th>
                                    {{--                                <th class="text-right">Mennyiség</th> --}}
                                    <th>Forrás</th>
                                    <th>Cél</th>
                                    <th>Létrehozás</th>
                                    <th>Megjegyzés</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($model as $product_movement)
                                    <tr @if ($product_movement->is_canceled) class="border-left-3 border-warning" @endif>
                                        {{--                                    <td><input type="checkbox"> {{ $product_movement->id }}</td> --}}
                                        <td>{{ $product_movement->id }}</td>
                                        <td>{{ $product_movement->reference_nr }}</td>
                                        {{-- <td>{{ $product_movement->stock_in > 0 ? $product_movement->stock_in : '-' . $product_movement->stock_out }}</td> --}}
                                        {{--                                    <td> --}}
                                        {{--                                        <strong>{{ $product_movement->product->title }}</strong> --}}
                                        {{--                                        <br><small>{{ $product_movement->product->isbn }}</small> --}}
                                        {{--                                    </td> --}}
                                        <td>{{ $product_movement->type }}</td>
                                        {{--                                    <td class="text-right">{!! $product_movement->quantity !!}</td> --}}
                                        <td>
                                            {{ $product_movement->source?->title }}
                                        </td>
                                        <td>
                                            @if (in_array($product_movement->destination_type, [
                                                    \Alomgyar\Product_movements\ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER,
                                                    \Alomgyar\Product_movements\ProductMovement::DESTINATION_TYPE_SHOP_ORDER,
                                                ]))
                                                Vásárló
                                            @elseif(
                                                $product_movement->destination_type === \Alomgyar\Product_movements\ProductMovement::DESTINATION_TYPE_VOID &&
                                                    $product_movement->source_type === 'merchant')
                                                Kereskedői fogyás elszámolás
                                            @else
                                                {{ $product_movement->destination?->title ?? $product_movement->comment_void }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product_movement->created_at->format('Y-m-d H:i') }}
                                            <br><small>{{ $product_movement->causer?->full_name }}</small>
                                        </td>
                                        <td>
                                            {{ $product_movement->comment_general }}
                                        </td>
                                        <td class="text-right">
                                            <div class="list-icons">
                                                <div class="btn-group ml-2">
                                                    <button type="button"
                                                        class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="icon-menu7"></i></button>

                                                    <div class="dropdown-menu" x-placement="top-start"
                                                        style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a href="/gephaz/warehouses/product_movements/{{ $product_movement->id }}"
                                                            target="_blank" class="dropdown-item">Bizonylat
                                                            megtekintése</a>
                                                        @if (in_array($product_movement->destination_type, [1, 2]))
                                                            @if ($product_movement->destination_id)
                                                                <a href="{{ route('orders.edit', ['order' => $product_movement->destination_id]) }}"
                                                                    target="_blank" class="dropdown-item">Rendeléslap
                                                                    megnyitása</a>
                                                            @else
                                                                <a href="{{ route('orders.edit', ['order' => \App\Order::where('order_number', Str::before($product_movement->comment_general, ' sz. rendelés sztornózva'))->first()->id ?? false]) }}"
                                                                    target="_blank" class="dropdown-item">Rendeléslap
                                                                    megnyitása</a>
                                                            @endif
                                                        @else
                                                            @can('product_movements.export')
                                                                <a href="{{ route('product_movements.export', ['ProductMovement' => $product_movement]) }}"
                                                                    class="dropdown-item">Szállítólevél készítés</a>
                                                            @endcan
                                                        @endif
                                                        @if (!$product_movement->is_canceled && $product_movement->source_type !== 'storno')
                                                            <button class="dropdown-item text-danger"
                                                                onclick="return confirm('Biztosan sztornózni kívánod a bizonylatot?') || event.stopImmediatePropagation()"
                                                                wire:click="storno({{ $product_movement->id }})">Bizonylat
                                                                sztornó</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
            table.table td {
                padding-top: 4px;
                padding-bottom: 4px;
            }
        </style>

    </div>
    @if ($loading)
        <div class="blockUI blockOverlay"
            style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(27, 32, 36); opacity: 0.85; cursor: wait; position: absolute;">
        </div>
        <div class="blockUI blockMsg blockElement"
            style="z-index: 1011; position: fixed; padding: 0px; margin: 0px; top: 50%; left: 50%; text-align: center; color: rgb(255, 255, 255); border: 0px; cursor: wait;">
            <h3>Kis türelmet kérek...</h3>
            <i class="icon-spinner4 spinner"></i>
        </div>
    @endif
</div>
@push('inline-js')
    <script>
        $(document).ready(function() {
            $('.byWarehouse').select2();
            $('.byWarehouse').on('change', function(e) {
                @this.set('byWarehouse', e.target.value);
            });
            $('.byShop').select2();
            $('.byShop').on('change', function(e) {
                @this.set('byShop', e.target.value);
            });
        });
    </script>
@endpush
