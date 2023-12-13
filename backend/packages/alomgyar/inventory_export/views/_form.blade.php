@isset($model)
    <form action="{{ route('inventory_export.update_product', ['product' => $model]) }}" method="POST" id="form">
    @else
        <form action="{{ route('inventory_export.store_product') }}" method="POST" id="form">
        @endisset
        @csrf
        <input type="hidden" name="warehouseID" value="{{ $warehouseID }}">
        <div class="card card-body justify-content-end">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="col-form-label font-weight-bold">Egy
                        termék kiválasztása (ISBN, ID vagy NÉV)</label>
                    <select id="product_id" class="form-control select-search" name="product_id" data-fouc
                        data-placeholder="Válassz egyet..." required
                        @isset($model)
                            disabled
                        @endisset>
                        @isset($model)
                            <option value="{{ $model->product_id }}" selected="selected">{{ $model->product->title }}
                                ({{ $model->product->isbn }})
                            </option>
                        @endisset
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label class="col-form-label font-weight-bold">Készlet</label>
                    <input name="stock" id="stock" class="form-control @error('stock') border-danger @enderror"
                        value="{{ !is_null(old('stock')) ? old('stock') : $model->stock ?? '' }}" type="number"
                        required>

                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-success legitRipple" title="Mentés">
                        Mentés
                    </button>
                </div>
            </div>
        </div>
    </form>
