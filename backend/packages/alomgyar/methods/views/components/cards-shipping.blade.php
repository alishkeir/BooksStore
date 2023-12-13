<div>
    <h5>Szállítási módok</h5>
    <div class="row" wire:sortable="updateOrder">
        @foreach($model as $shippingMethod)
            <div class="col-md-7" wire:key="item-{{ $shippingMethod->id }}">
                <div class="card">
    
                    <div class="card-body">
                        <strong>{{ $shippingMethod->name  }}</strong>
                        <br><br>
                        <div class=" d-flex justify-content-between">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item mr-2">
                                Álomgyár: {{$shippingMethod->fee_0}} Ft 
                            </li>
                            <li class="list-inline-item mr-2">
                                Olcsók.: {{$shippingMethod->fee_1}} Ft 
                            </li>
                            <li class="list-inline-item mr-2">
                                Nagyker: {{$shippingMethod->fee_2}} Ft 
                            </li>
                        </ul>
                        </div>
                    </div>
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">{{ __('messages.updated') }}: {{ $shippingMethod->updated_at->diffForHumans() }}</span>

                    <ul class="list-inline mb-0">
                        <li class="list-inline-item mr-2">
                            <a href="{{ route('shipping-method.edit', $shippingMethod) }}" class="text-blue">
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
