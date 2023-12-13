<div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <select wire:model="selectedShopId" class="form-control">
                    <option value="0">Álomgyár</option>
                    <option value="1">Olcsókönyvek</option>
                    <option value="2">Nagyker</option>
                </select>
            </div>
        </div>
    </div>



    <div class="row" wire:sortable="updateOrder">
        @foreach($model as $carousel)
            <div class="col-md-3" wire:sortable.item="{{ $carousel->id }}" wire:key="carousel-{{ $carousel->id }}">
                <div class="card">
                    <div class="card-img-actions mx-1 mt-1">
                        <a href="{{ route('carousels.edit', ['carousel' => $carousel]) }}">
                            <img class="card-img-top img-fluid" src="{{ $carousel->getFirstMediaUrl('featured_image') }}" alt="">
                        </a>
                    </div>

                    <div class="card-body">
                        <img src="{{ asset('storage/'.$carousel->image) }}" alt="{{ $carousel->title }}" class="img-fluid">
                        <div class="pt-2">
                            {{ $carousel->title  }}
                        </div>
                        @if(isset($carousel->url) && $carousel->url !== '')
                            <div class="pt-2">
                                <a href="{{ $carousel->url }}" target="_blank">{{ $carousel->url }}</a>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">{{ __('messages.updated') }}: {{ $carousel->updated_at->diffForHumans() }}</span>

                        <ul class="list-inline mb-0">
                            @hasrole('skvadmin')
                            <li class="list-inline-item mr-2">
                                <a href="javascript:;"
                                   class="text-danger"
                                   onclick="return confirm('{{ __('messages.delete-confirm') }}')  || event.stopImmediatePropagation()"
                                   wire:click="destroy({{ $carousel->id }})">
                                    <i class="icon-trash"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                {!! $carousel->status_html !!}
                            </li>
                            @endhasrole
                        </ul>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>
