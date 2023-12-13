<div>
    <h5>Fizetési módok</h5>
    <div class="row" wire:sortable="updateOrder">
    @foreach($model as $methods)
        <div class="col-md-7" wire:key="item-{{ $methods->id }}">
            <div class="card">

                <div class="card-body">
                    <strong>{{ $methods->name  }}</strong>
                    <br><br>
                    <div class=" d-flex justify-content-between">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item mr-2">
                            Álomgyár: {{$methods->fee_0}} Ft 
                        </li>
                        <li class="list-inline-item mr-2">
                            Olcsók.: {{$methods->fee_1}} Ft 
                        </li>
                        <li class="list-inline-item mr-2">
                            Nagyker: {{$methods->fee_2}} Ft 
                        </li>
                    </ul>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">{{ __('messages.updated') }}: {{ $methods->updated_at->diffForHumans() }}</span>

                    <ul class="list-inline mb-0">
                        <li class="list-inline-item mr-2">
                            <a href="{{ route('methods.edit', ['method' => $methods]) }}" class="text-blue">
                                <i class="icon-pencil7"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
    </div>
    
</div>
