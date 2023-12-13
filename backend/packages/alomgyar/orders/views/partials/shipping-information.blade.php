@if (!$order->onlyEbook())

    <div style="text-align: center">
        <h3>
            @if ($order->shippinMethond !== 'home') Átvétellel
            @else
                Szállítással @endif kapcsolatos információk
        </h3>
    </div>



    @if ($order->shippingMethod->method_id === 'home' or $order->shippingMethod->method_id === 'sameday' or $order->shippingMethod->method_id === 'dpd')
        <table style="width: 100%;">
            <tr>
                <td>Átvétel:</td>
                <td>{{ $order->shippingMethod->name ?? '' }}</td>
            </tr>

            <tr>
                <td>Név:</td>
                <td>{{ $order->shippingAddress->last_name ?? '' }} {{ $order->shippingAddress->first_name ?? '' }}</td>
            </tr>
            @if ($order->shippingAddress->vat_number ?? false)
                <tr>
                    <td>Adószám:</td>
                    <td>{{ $order->shippingAddress->vat_number ?? '' }}</td>
                </tr>
            @endif
            <tr>
                <td>Ország:</td>
                <td>{{ $order->shippingAddress->country->name ?? '' }}</td>
            </tr>
            <tr>
                <td>Város:</td>
                <td>{{ $order->shippingAddress->zip_code ?? '' }}, {{ $order->shippingAddress->city ?? '' }}</td>
            </tr>
            <tr>
                <td>Cím:</td>
                <td>{{ $order->shippingAddress->address ?? '' }}</td>
            </tr>
            @if ($order->customer->phone)
                <tr>
                    <td>Telefonszám:</td>
                    <td>{{ $order->customer->phone }}</td>
                </tr>
            @endif

            <tr>
                <td>Megjegyzés:</td>
                <td>{{ $order->shippingAddress->comment ?? '' }}</td>
            </tr>
        </table>
    @elseif ($order->shippingObject)
        @if (($order->shippingMethod->method_id ?? false) != 'shop' && ($order->shippingMethod->method_id ?? false) != 'box')
            <table style="width: 100%;">
                <tr>
                    <td>Átvétel:</td>
                    <td>{{ $order->shippingMethod->name ?? '' }}</td>
                </tr>
                <tr>
                    <td>Név:</td>
                    <td>{{ $order->shipping_details->last_name ?? '' }}
                        {{ $order->shipping_details->first_name ?? '' }}</td>
                </tr>
                @if ($order->shipping_details->vat_number ?? false)
                    <tr>
                        <td>Adószám:</td>
                        <td>{{ $order->shipping_details->vat_number ?? '' }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Ország:</td>
                    <td>{{ $order->shipping_details->country->name ?? '' }}</td>
                </tr>
                <tr>
                    <td>Város:</td>
                    <td>{{ $order->shipping_details->zip_code ?? '' }}, {{ $order->shipping_details->city ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td>Cím:</td>
                    <td>{{ $order->shipping_details->address ?? '' }}</td>
                </tr>
                @if ($order->customer->phone)
                    <tr>
                        <td>Telefonszám:</td>
                        <td>{{ $order->customer->phone }}</td>
                    </tr>
                @endif

                <tr>
                    <td>Megjegyzés:</td>
                    <td>{{ $order->shipping_details->comment ?? '' }}</td>
                </tr>
            </table>
            {{-- @include('partials.order.shipping-info-details', [
                'lastName' => $model->shipping_details->last_name,
                'firstName' => $model->shipping_details->first_name,
                'vatNumber' => $model->shipping_details->vat_number,
                'countryName' => $model->shipping_details->country->name,
                'zipCode' => $model->shipping_details->zip_code,
                'city' => $model->shipping_details->city,
                'address' => $model->shipping_details->address,
                'comment' => $model->shipping_details->comment,
            ]) --}}
        @else
            <table style="width: 100%">
                <tr>
                    <td>Átvételi pont:</td>
                    <td>{{ $order->shippingObject->getProviderName() }}</td>
                </tr>
                @if ($order->shippingMethod->method_id === 'box')
                    <tr>
                        <td>Átvételi pont azonosítója:</td>
                        <td>{{ $order->shippingObject->getProviderId() }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Név:</td>
                    <td>{{ $order->shippingObject->getName() }}</td>
                </tr>
                <tr>
                    <td>Cím:</td>
                    <td>{{ $order->shippingObject->getFullAddress() }}</td>
                </tr>
                @if ($order->shippingObject->getOpening())
                    <tr>
                        <td>Nyitvatartás:</td>
                        <td>{{ $order->shippingObject->getOpening() }}</td>
                    </tr>
                @endif
                @if ($order->shippingObject->getDescription())
                    <tr>
                        <td>Megjegyzés:</td>
                        <td>{{ $order->shippingObject->getDescription() }}</td>
                    </tr>
                @endif
            </table>
        @endif
    @endif

@endisset
