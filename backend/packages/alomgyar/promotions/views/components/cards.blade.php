<div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                @include('admin::partials._search')
            </div>
        </div>
    </div>
    <div class="row" wire:sortable="updateOrder">
        @foreach($model as $item)
            <div class="col-md-3" {{--wire:sortable.item="{{ $item->id }}"--}} wire:key="item-{{ $item->id }}">
                <div class="card">
                    <div class="card-img-actions mx-1 mt-1">
                        <a href="{{ route('promotions.edit', ['promotion' => $item]) }}">
                            <img class="card-img-top img-fluid" src="/storage/{{ $item->list_image_sm }}" alt="">
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="float-right">
                            @if($item->store_0 == 1)<i class="icon icon-check text-danger"></i>@endif
                            @if($item->store_1 == 1)<i class="icon icon-check" style="color:#fbc72e;"></i>@endif
                            @if($item->store_2 == 1)<i class="icon icon-check" style="color:#4971ff;"></i>@endif
                        </div>
                        <strong>{!! $item->title !!}</strong>
                        <br>{{ substr($item->active_from, 0, 10) }} - {{ substr($item->active_to, 0, 10) }}
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">{{ __('messages.updated') }}: {{ $item->updated_at->diffForHumans() }}</span>

                        <ul class="list-inline mb-0">
                            <li class="list-inline-item mr-2">
                                <a href="{{ route('promotions.edit', ['promotion' => $item]) }}" class="text-blue">
                                    <i class="icon-pencil7"></i>
                                </a>
                            </li>
                            @hasrole('skvadmin')
                            <li class="list-inline-item mr-2">
                                <a href="javascript:"
                                   class="text-danger"
                                   onclick="return confirm('{{ __('messages.delete-confirm') }}')  || event.stopImmediatePropagation()"
                                   wire:click="destroy({{ $item->id }})">
                                    <i class="icon-trash"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                {!! $item->status_html !!}
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
