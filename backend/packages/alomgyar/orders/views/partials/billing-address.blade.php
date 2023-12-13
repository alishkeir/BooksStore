@isset($order->billingAddress)

    <div style="text-align: center">
        <h3>Számlázással kapcsolatos információk</h3>
    </div>

    @if($order->billingAddress->entity_type == 1)
        <p><strong>Magánszemély</strong></p>
    @else
        <p><strong>Céges</strong></p>
        <p>{{$order->billingAddress->business_name}}</p>
    @endif

    <table style="width: 100%">
        <tr>
            <td>Név:</td>
            <td>{{$order->billingAddress->last_name}} {{$order->billingAddress->first_name}}</td>
        </tr>
        @if($order->billingAddress->vat_number ?? false)
            <tr>
                <td>Adószám:</td>
                <td>{{$order->billingAddress->vat_number}}</td>
            </tr>
        @endif
        <tr>
            <td>Ország:</td>
            <td>{{$order->billingAddress?->country?->name}}</td>
        </tr>
        <tr>
            <td>Város:</td>
            <td>{{$order->billingAddress?->zip_code}}, {{$order->billingAddress?->city}}</td>
        </tr>
        <tr>
            <td>Cím:</td>
            <td>{{$order->billingAddress?->address}}</td>
        </tr>
        @if($order->customer->email)
            <tr>
                <td>Email cím:</td>
                <td>{{$order->customer->email}}</td>
            </tr>
        @endif
        @if($order->customer->phone)
            <tr>
                <td>Telefonszám:</td>
                <td>{{$order->customer->phone}}</td>
            </tr>
        @endif
        <tr>
            <td>Megjegyzés:</td>
            <td>{{$order->billingAddress->comment}}</td>
        </tr>
    </table>
@endif
