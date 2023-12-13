<div>
    <div class="card card-body" style="margin:-20px; margin-bottom:20px;">
        @include('admin::partials._search')
    </div>
    <div class="row" wire:sortable="updateOrder">
        @foreach($model as $category)
            <div class="col-md-3" wire:sortable.category="{{ $category->id }}" wire:key="category-{{ $category->id }}">
                <div class="card">
                    {{--<div class="card-img-actions mx-1 mt-1">
                        <a href="{{ route('categories.edit', ['category' => $category]) }}">
                            <img class="card-img-top img-fluid" src="{{ $category->getFirstMediaUrl('featured_image') }}" alt="">
                        </a>
                    </div>--}}

                    <div class="card-body d-flex justify-content-between p-3">
                        {{ $category->title  }}
                        {!! $category->status_html !!}
                    </div>

                    <div class="card-footer d-flex justify-content-between p-2">
                        <span class="text-muted">Alkategóriák: {{ count( $category->subcategories ?? [] ) }}</span>
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item mr-2">
                                <a href="{{ route('categories.edit', ['category' => $category]) }}" class="text-blue">
                                    <i class="icon-pencil7"></i>
                                </a>
                            </li>
                            @hasrole('skvadmin')
                            <li class="list-inline-item mr-2">
                                <a href="javascript:;"
                                   class="text-danger"
                                   onclick="return confirm('{{ __('messages.delete-confirm') }}')  || event.stopImmediatePropagation()"
                                   wire:click="destroy({{ $category->id }})">
                                    <i class="icon-trash"></i>
                                </a>
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
                @include('admin::partials._pagination')
        </div>
    </div>
</div>
