<div class="tab-pane fade show" id="orders">
    @livewire('orderlist', ['customer' => $model->id])
    
    {{--}}
    <div class="row">
        <div class="col-lg-12">

            <div class="card-group-control card-group-control-right">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $model->full_name }} rendelései
                        </h3>
                    </div>
                </div>

                @foreach($model->orders as $order)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-default w-75" href="#collapse-{{ $order->id }}">
                                    <div class="row">
                                        <div class="col-3">#{{ $order->id }} sz. rendelés</div>
                                        <div class="col-3">{{ $order->created_at }}</div>
                                        <div class="col-3">{{ $order->total_amount }} Ft</div>
                                        <div class="col-3">{!! $order->status_html !!}</div>
                                    </div>
                                </a>
                            </h6>
                        </div>

                        <div id="collapse-{{ $order->id }}" class="collapse">
                            <div class="card-body">
                                <div class="d-table w-100 border-bottom-2">
                                    @foreach($order->orderItems as $item)
                                        <div class="d-table-row">
                                            <div class="d-table-cell p-2">
                                                <div style="display: grid;grid-template-columns: 80px auto;grid-auto-flow: column;grid-template-rows: 25% 75%;">
                                                    <img src="{{ $item->product_cover }}" alt="" class="img-thumbnail" style="max-width: 70px;/* grid-column:1; */grid-column: 1;grid-row: 1 / 3;">
                                                    <p class="text-muted">{{ $item->author_title }}</p>
                                                    <p class="font-weight-bold">{{ $item->product_title }}</p>
                                                </div>
                                            </div>
                                            <div class="d-table-cell text-right align-top p-2">
                                                {{ $item->price }} Ft
                                            </div>
                                            <div class="d-table-cell text-center align-top p-2">
                                                {{ $item->quantity }} db
                                            </div>
                                            <div class="d-table-cell text-right align-top p-2">
                                                <span class="font-weight-bold">{{ $item->total }} Ft</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="d-table-row">
                                        <div class="d-table-cell align-top p-2">
                                            <h5 class="font-weight-bold">Szállítási díj</h5>
                                        </div>
                                        <div class="d-table-cell text-center align-top p-2">

                                        </div>
                                        <div class="d-table-cell text-center align-top p-2">

                                        </div>
                                        <div class="d-table-cell text-right align-top p-2">
                                            <h5 class="font-weight-bold">{{ $order->shipping_fee }} Ft</h5>
                                        </div>
                                    </div>
                                    <div class="d-table-row">
                                        <div class="d-table-cell align-top p-2">
                                            <h5 class="font-weight-bold">Összesen</h5>
                                        </div>
                                        <div class="d-table-cell text-center align-top p-2">

                                        </div>
                                        <div class="d-table-cell text-center align-top p-2">

                                        </div>
                                        <div class="d-table-cell text-right align-top p-2">
                                            <h5 class="font-weight-bold">{{ $order->total_amount }} Ft</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex border-bottom-2 justify-content-around">
                                    <div>
                                        <h4>Számlázási adatok</h4>
                                        <p>{{ $order->billingAddress?->full_name }}</p>
                                        <p>{{ $order->billingAddress?->zip_code }} {{ $order->billingAddress?->city }}</p>
                                        <p>{{ $order->billingAddress?->street }} {{ $order->billingAddress?->street_nr }}</p>
                                    </div>
                                    <div>
                                        <h4>Szállítási adatok</h4>
                                        <p>{{ $order->shippingAddress?->full_name }}</p>
                                        <p>{{ $order->shippingAddress?->zip_code }} {{ $order->shippingAddress?->city }}</p>
                                        <p>{{ $order->shippingAddress?->street }} {{ $order->shippingAddress?->street_nr }}</p>
                                    </div>
                                    <div>
                                        <h4>Fizetési mód</h4>
                                        <p>{{ $order->paymentMethod?->name }}</p>
                                    </div>
                                    <div>
                                        <h4>Szállítási mód</h4>
                                        <p>{{ $order->shippingMethod?->name }}</p>
                                    </div>
                                </div>
                                <div class="d-inline-block text-danger-600 font-weight-semibold p-3">
                                    <a href="{{ $order->invoice_url }}">Számla letöltése (PDF)</a>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('managment_templates.order') }}">Rendelés módosítása</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>--}}
</div>
