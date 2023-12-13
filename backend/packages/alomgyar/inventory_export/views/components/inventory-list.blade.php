<div class="row">
    <div class="col-lg-12">
        <div class="card card-body">
            <div class="row d-flex justify-content-center align-items-end">
                <div class="col-md-6">
                    <label for="warehouse_id" class="col-form-label font-weight-bold">Bolt</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-control select" wire:model="warehouseID">
                        <option></option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">
                                {{ $warehouse->shop?->title ?? $warehouse->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    @if ($warehouseID)
                        <a href="{{ route('inventory_export.create_product', ['warehouseID' => $warehouseID]) }}"
                            class="btn btn-outline-success">Könyv hozzáadása</a>
                        <a href="#" class="btn btn-outline-info ml-5"
                            onclick="confirm('Az üzlet készletének frissítése után a megszámlált adatok törlődnek. biztos vagy ebben??') || event.stopImmediatePropagation()"
                            wire:click="updateStoreInventory">Bolt raktárkészletének frissítése</a>
                    @endif
                </div>
            </div>
        </div>
        @if ($warehouseID)
            <div class="card card-body">
                @include('admin::partials._search')
                <table class="table table-striped">
                    <thead>
                        <th>ISBN</th>
                        <th>CÍM</th>
                        <th>KÉSZLET</th>
                        <th>Művelet</th>
                    </thead>
                    <tbody>
                        @foreach ($warehouseProducts as $item)
                            <tr>
                                <td>{{ $item->product->isbn }}</td>
                                <td>{{ $item->product->title }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>
                                    <div class="list-icons">
                                        <a href="{{ route('inventory_export.edit_product', ['inventoryProduct' => $item->id, 'warehouseID' => $warehouseID]) }}"
                                            class="btn btn-outline alpha-primary text-primary-800 border-primary-600 legitRipple"><i
                                                class="icon-pencil7"></i></a>
                                        <form
                                            action="{{ route('inventory_export.delete_product', ['product' => $item]) }}"
                                            class="d-inline" method="POST"
                                            onsubmit="return confirm('{{ __('messages.delete-confirm') }}');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-outline alpha-danger text-danger-800 border-danger-600 legitRipple">
                                                <i class="icon-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @include('admin::partials._pagination', ['model' => $warehouseProducts])
            </div>
        @endif
    </div>
</div>
