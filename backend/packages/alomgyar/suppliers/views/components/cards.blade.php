<div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                @include('admin::partials._search')
            </div>
        </div>
    </div>
    <div class="row" wire:sortable="updateOrder">
        @foreach($model as $supplier)
            <div class="col-md-3" wire:sortable.supplier="{{ $supplier->id }}" wire:key="supplier-{{ $supplier->id }}">
                <div class="card">
                    <div class="card-img-actions mx-1 mt-1">
                        <a href="{{ route('suppliers.edit', ['supplier' => $supplier]) }}">
                            <img class="card-img-top img-fluid" src="{{ $supplier->getFirstMediaUrl('featured_image') }}" alt="">
                        </a>
                    </div>

                    <div class="card-body">
                        {{ $supplier->title  }}
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">{{ __('messages.updated') }}: {{ $supplier->updated_at->diffForHumans() }}</span>

                        <ul class="list-inline mb-0">
                            <li class="list-inline-supplier mr-2">
                                <a href="{{ route('suppliers.edit', ['supplier' => $supplier]) }}" class="text-blue">
                                    <i class="icon-pencil7"></i>
                                </a>
                            </li>
                            @hasrole('skvadmin')
                            <li class="list-inline-supplier mr-2">
                                <a href="javascript:"
                                   class="text-danger"
                                   onclick="return confirm('{{ __('messages.delete-confirm') }}')  || event.stopImmediatePropagation()"
                                   wire:click="destroy({{ $supplier->id }})">
                                    <i class="icon-trash"></i>
                                </a>
                            </li>
                            <li class="list-inline-supplier">
                                {!! $supplier->status_html !!}
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
