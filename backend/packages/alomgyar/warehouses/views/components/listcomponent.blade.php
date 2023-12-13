<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="actual">
                @include('admin::partials._search')
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('id')" role="button" class="text-default">
                                    #
                                    @include('admin::partials._sort-icons', ['field' => 'id'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('title')" role="button" class="text-default">
                                    Név
                                    @include('admin::partials._sort-icons', ['field' => 'title'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Előtag
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('city')" role="button" class="text-default">
                                    Város
                                    @include('admin::partials._sort-icons', ['field' => 'city'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Raktár típus
                                </a>
                            </th>
                            <th>Bolt neve</th>
                            <th>Készlet</th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('status')" role="button" class="text-default">
                                    Állapot
                                    @include('admin::partials._sort-icons', ['field' => 'status'])
                                </a>
                            </th>
                            <th width="10%">{{ Str::ucfirst(__('general.actions')) }}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Név</th>
                            <th>Előtag</th>
                            <th>Város</th>
                            <th>Raktár típus</th>
                            <th>Bolt neve</th>
                            <th>Készlet</th>
                            <th>Állapot</th>
                            <th>Műveletek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $warehouse)
                        <tr>
                            <td>{{ $warehouse->id }}</td>
                            <td><strong>{{ $warehouse->title }}</strong></td>
                            <td>{{ $warehouse->invoice_prefix }}</td>
                            <td>{{ $warehouse->city }}</td>
                            <td>{{ $warehouse->warehousetype }}</td>
                            <td>{{ $warehouse->shop->title ?? '' }}</td>
                            <td>{{ $warehouse->TotalInventory }}</td>
                            <td>@if( $warehouse->status ?? false)<span class="badge badge-success">Látható</span>@endif</td>
                            <td>
                                <div class="list-icons">
                                    <a href="/gephaz/products?warehouse={{$warehouse->id}}&tab=tab-2" class="btn btn-outline alpha-info text-info-800 border-info-600 legitRipple" target="_blank">Terméklista</a>
                                    <a href="{{ route('warehouses.export', ['warehouseID' => $warehouse->id]) }}" class="btn btn-outline alpha-green text-green-800 border-green-600 legitRipple">Export</a>
                                    <a href="{{ route('warehouses.import', ['warehouseID' => $warehouse->id]) }}" class="btn btn-outline alpha-violet text-violet-800 border-violet-600 legitRipple">Leltár</a>
                                    @can('warehouses.storing')
                                    <a href="{{ route('warehouses.edit', ['warehouse' => $warehouse]) }}" class="btn btn-outline alpha-primary text-primary-800 border-primary-600 legitRipple"><i class="icon-pencil7"></i></a>
                                    @endcan
                                    @can('warehouses.destroy')
                                        <form action="{{route('warehouses.destroy', ['warehouse' => $warehouse])}}" class="d-inline" method="POST" onsubmit="return confirm('{{ __('messages.delete-confirm') }}');">
                                            @csrf
                                            @method('DELETE')
{{--                                            <button type="submit" class="btn btn-danger btn-sm btn-link list-icons-document text-danger-600 p-0"> <i class="icon-trash"></i></button>--}}
                                            <button type="submit" class="btn btn-outline alpha-danger text-danger-800 border-danger-600 legitRipple"> <i class="icon-trash"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('admin::partials._pagination')
            </div>
        </div>
    </div>
</div>
