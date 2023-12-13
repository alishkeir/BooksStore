<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title font-weight-semibold">
                    <i class="icon-database-export mr-2"></i>
                    Forrás
                </h6>
                <div class="header-elements">
                    <span class="text-muted"></span>
                </div>
            </div>
            <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0 mt-3">
                <li class="nav-item"><a href="#tab-warehouse" wire:click.prevent="setTab('tab-warehouse')"
                        class="nav-link legitRipple {{ $tab === 'tab-warehouse' ? 'active' : '' }}"
                        data-toggle="tab">Raktár</a></li>
                <li class="nav-item"><a href="#tab-supplier" wire:click.prevent="setTab('tab-supplier')"
                        class="nav-link legitRipple {{ $tab === 'tab-supplier' ? 'active' : '' }}"
                        data-toggle="tab">Beszerzés</a></li>
            </ul>
            <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
                <div class="tab-pane fade @if ($tab === 'tab-warehouse') show active @endif" id="tab-warehouse">
                    <div class="card-body @if ($tab !== 'tab-warehouse') d-none @endif">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Raktár</label>
                                <select
                                    class="form-control select-search-warehouse-sourceid  @error('source_id') border-danger @enderror"
                                    wire:model="source_id">
                                    <option value="0">Válassz egy raktárt</option>
                                    @foreach ($this->warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->title }}
                                            ({{ $wh->productInventory($product_id) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('source_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if (!$bulkProducts && $product_id)
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold">Mennyiség</label>
                                    <input wire:model.debounce.500ms="stock_in" type="number"
                                        class="form-control @error('stock_in') border-danger @enderror"
                                        value="{{ !is_null(old('stock_in')) ? old('stock_in') : $model->stock_in ?? 0 }}">
                                    @error('stock_in')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="tab-pane fade @if ($tab === 'tab-supplier') active show @endif" id="tab-supplier">
                    <div class="card-body @if ($tab !== 'tab-supplier') d-none @endif">

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Beszállító</label>
                                <select
                                    class="form-control select-search-supplier @error('source_id') border-danger @enderror"
                                    wire:model="source_id">
                                    <option>Válassz egy beszállítót</option>
                                    @foreach ($this->suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->title }}</option>
                                    @endforeach
                                </select>
                                @error('source_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if (!$bulkProducts && $product_id)
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="col-form-label font-weight-bold">Mennyiség</label>
                                    <input wire:model.lazy="stock_in" type="number"
                                        class="form-control @error('stock_in') border-danger @enderror"
                                        value="{{ !is_null(old('stock_in')) ? old('stock_in') : $model->stock_in ?? 0 }}"
                                        min="0">
                                    @error('stock_in')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label font-weight-bold">Beszerzési ár</label>
                                    <input wire:model.lazy="purchase_price" type="number"
                                        class="form-control @error('purchase_price') border-danger @enderror"
                                        value="{{ !is_null(old('purchase_price')) ? old('purchase_price') : $model->purchase_price ?? 0 }}"
                                        min="0">
                                    @error('purchase_price')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- DESTINATION -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title font-weight-semibold">
                    <i class="icon-database-insert mr-2"></i>
                    Cél
                </h6>
                <div class="header-elements">
                    <span class="text-muted"></span>
                </div>
            </div>
            <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0 mt-3">
                <li class="nav-item"><a href="#destination-warehouse"
                        wire:click.prevent="setDestinationTab('tab-warehouse')"
                        class="nav-link legitRipple {{ $destinationTab === 'tab-warehouse' ? 'active' : '' }}"
                        data-toggle="tab">Raktár</a></li>
                <li class="nav-item"><a href="#destination-other" wire:click.prevent="setDestinationTab('tab-other')"
                        class="nav-link legitRipple {{ $destinationTab === 'tab-other' ? 'active' : '' }}"
                        data-toggle="tab">Egyéb</a></li>
            </ul>
            <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
                <div class="tab-pane fade @if ($destinationTab === 'tab-warehouse') active show @endif"
                    id="destination-warehouse">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Raktár</label>
                                <select
                                    class="form-control select2 select-search-warehouse-destinationid @error('destination_id') border-danger @enderror"
                                    wire:model="destination_id">
                                    <option value="0">Válassz egy raktárt</option>
                                    @foreach ($this->warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->title }}
                                            ({{ $wh->productInventory($product_id) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if ($destinationTab === 'tab-other') active show @endif" id="destination-other">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Szállítólevélen
                                    megjelenik:</label>
                                <textarea class="form-control" wire:model.defer.500ms="comment_void"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- COMMENT -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title font-weight-semibold">
                    <i class="icon-database-insert mr-2"></i>
                    Megjegyzések
                </h6>
                <div class="header-elements">
                    <span class="text-muted"></span>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <label class="col-form-label font-weight-bold">Csak belső használatra</label>
                        <textarea class="form-control" wire:model.defer.500ms="comment_general"></textarea>
                    </div>
                    <div class="col-lg-6">
                        <label class="col-form-label font-weight-bold">Szállítólevél alján jelenik meg</label>
                        <textarea class="form-control" wire:model.defer.500ms="comment_bottom"></textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('inline-js')
    <script>
        $(document).ready(function() {
            $('.supplierSourceId').select2();
            $('.supplierSourceId').on('change', function(e) {
                @this.set('source_id', e.target.value);
            });

        });
    </script>
@endpush
