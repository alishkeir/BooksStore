<div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                @include('admin::partials._search')
            </div>
        </div>
    </div>
    <div class="row" wire:sortable="updateOrder">
        @foreach($model as $warehouse)
            <div class="col-md-3" wire:sortable.warehouse="{{ $warehouse->id }}" wire:key="warehouse-{{ $warehouse->id }}">
                <div class="card">
                    <div class="card-img-actions mx-1 mt-1">
                        <a href="{{ route('warehouses.edit', ['warehouse' => $warehouse]) }}">
                            <img class="card-img-top img-fluid" src="{{ $warehouse->getFirstMediaUrl('featured_image') }}" alt="">
                        </a>
                    </div>

                    <div class="card-body">
                        {{ $warehouse->title  }}
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">{{ __('messages.updated') }}: {{ $warehouse->updated_at->diffForHumans() }}</span>

                        <ul class="list-inline mb-0">
                            <li class="list-inline-warehouse mr-2">
                                <a href="{{ route('warehouses.edit', ['warehouse' => $warehouse]) }}" class="text-blue">
                                    <i class="icon-pencil7"></i>
                                </a>
                            </li>
                            @hasrole('skvadmin')
                            <li class="list-inline-warehouse mr-2">
                                <a href="javascript:;"
                                   class="text-danger"
                                   onclick="return confirm('{{ __('messages.delete-confirm') }}')  || event.stopImmediatePropagation()"
                                   wire:click="destroy({{ $warehouse->id }})">
                                    <i class="icon-trash"></i>
                                </a>
                            </li>
                            <li class="list-inline-warehouse">
                                {!! $warehouse->status_html !!}
                            </li>
                            @endhasrole
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                @include('admin::partials._pagination')
            </div>
        </div>
    </div>
</div>
