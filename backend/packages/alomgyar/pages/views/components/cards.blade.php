<div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                @include('admin::partials._search')
            </div>
        </div>
    </div>
    <div class="row" wire:sortable="updateOrder">
        @foreach($model as $page)
            <div class="col-md-3" wire:sortable.page="{{ $page->id }}" wire:key="page-{{ $page->id }}">
                <div class="card">
                    <div class="card-img-actions mx-1 mt-1">
                        <a href="{{ route('pages.edit', ['page' => $page]) }}">
                            <img class="card-img-top img-fluid" src="{{ $page->getFirstMediaUrl('featured_image') }}" alt="">
                        </a>
                    </div>

                    <div class="card-body">
                        {{ $page->title  }}
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">{{ __('messages.updated') }}: {{ $page->updated_at->diffForHumans() }}</span>

                        <ul class="list-inline mb-0">
                            <li class="list-inline-page mr-2">
                                <a href="{{ route('pages.edit', ['page' => $page]) }}" class="text-blue">
                                    <i class="icon-pencil7"></i>
                                </a>
                            </li>
                            @hasrole('skvadmin')
                            <li class="list-inline-page mr-2">
                                <a href="javascript:;"
                                   class="text-danger"
                                   onclick="return confirm('{{ __('messages.delete-confirm') }}')  || event.stopImmediatePropagation()"
                                   wire:click="destroy({{ $page->id }})">
                                    <i class="icon-trash"></i>
                                </a>
                            </li>
                            <li class="list-inline-page">
                                {!! $page->status_html !!}
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
