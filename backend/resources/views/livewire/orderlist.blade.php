<div>
    {{-- Ez a template a könyvnél és a customernél van felhasználva, a megrendelések lista a package-nél található --}}

    <div class="card">
        <div class="card-header bg-transparent">
            @include('customers::components._search')
        </div>
        <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            <a href="javascript:" wire:click.prevent="sortBy('id')" role="button" class="text-default">
                                Rendelésszám
                                @include('admin::partials._sort-icons', ['field' => 'orders.id'])
                            </a>
                        </th>
                        <th>
                            <a href="javascript:" wire:click.prevent="sortBy('total_amount')" role="button"
                                class="text-default">
                                Ár
                                @include('admin::partials._sort-icons', ['field' => 'orders.total_amount'])
                            </a>
                        </th>
                        <th>
                            <a href="javascript:" wire:click.prevent="sortBy('shipping_fee')" role="button"
                                class="text-default">
                                Szállítási mód/díj
                                @include('admin::partials._sort-icons', ['field' => 'shipping_fee'])
                            </a>
                        </th>
                        <th> Fizetési mód/díj</th>
                        <th>
                            <a href="javascript:" wire:click.prevent="sortBy('created_at')" role="button"
                                class="text-default">
                                Dátum
                                @include('admin::partials._sort-icons', ['field' => 'orders.created_at'])
                            </a>
                        </th>
                        <th>
                            <a href="javascript:" wire:click.prevent="sortBy('status')" role="button"
                                class="text-default">
                                Állapot
                                @include('admin::partials._sort-icons', ['field' => 'orders.status'])
                            </a>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Rendelésszám</th>
                        <th>Ár</th>
                        <th>Szállítási mód/díj</th>
                        <th>Fizetési mód/díj</th>
                        <th>Dátum</th>
                        <th>Állapot</th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse($model as $order)
                        <tr
                            style="border-left:3px solid @if ($order->store == 0) #e62934; @elseif($order->store == 1) #fbc72e @elseif($order->store == 2) #4971ff;  @else  #000; @endif">


                            <td
                                @if ($order->has_ebook) title="Tartalmaz e-bookot" class="text-success" @endif>
                                {{ $order->order_number }}
                            </td>
                            {{-- <td>{{$order->total_amount}} Ft
                <br><small>{{$order->total_quantity}} db</small></td> --}}
                            <td>
                                @if (isset($product))
                                    @php
                                        $itemData = $order->orderItems->where('product_id', $product)->first();
                                    @endphp
                                    {{ $itemData->total * $itemData->quantity }} Ft
                                    <br><small>{{ $itemData->quantity }} db</small>
                                @else
                                    {{ $order->total_amount }} Ft
                                    <br><small>{{ $order->total_quantity }} db</small>
                                @endif
                            </td>

                            <td>
                                <strong style="white-space: nowrap;">{{ $order->shippingMethod?->name }} </strong>
                                <br><small>{{ $order->shipping_fee }} Ft</small>
                            </td>
                            <td>
                                <strong style="white-space: nowrap;">{{ $order->paymentMethod?->name }}</strong>
                                <br><small>{{ $order->payment_fee ?? 'N/A' }} Ft</small>
                            </td>
                            <td>
                                <strong>{{ substr($order->created_at, 0, 10) }}</strong>
                                <br>{{ substr($order->created_at, 10, 9) }}
                            </td>
                            <td>
                                {!! $order->status_html !!}
                                <small>{!! $order->payment_status_html !!}</small>

                            </td>
                            <td class="text-right px-0" style="max-width:125px;">
                                <div class="list-icons">

                                    @if (!empty($order->attachments))
                                        <div class="btn-group ml-2">
                                            <button type="button"
                                                class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                data-toggle="dropdown" aria-expanded="false">
                                                @if ($order->invoice_url != '')
                                                    <i class="icon icon-file-check text-success"></i>
                                                @endif
                                            </button>
                                            <div class="dropdown-menu" x-placement="top-start"
                                                style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">

                                                @foreach ($order->attachments as $attachment)
                                                    <a href="{{ route('orders.invoice.get', ['id' => $attachment]) }}"
                                                        target="_blank"
                                                        class="dropdown-item @if ($attachment == $order->invoice_url) bg-success @endif"><strong>{{ $attachment }}</strong></a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <a target="blank_" href="/gephaz/orders/{{ $order->id }}"
                                        class="btn alpha-primary text-primary-800 btn-icon ml-0 legitRipple"><i
                                            class="icon-file-eye2"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-4 text-center">
                                <h5>Nincs megjeleníthető rendelés</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @include('admin::partials._pagination')
        </div>
    </div>

</div>
