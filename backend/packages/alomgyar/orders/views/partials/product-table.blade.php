<table style='width:100%;margin-top:15px;'>
    <tbody style="background-color:#f9fafb;font-size:.9em;">
    @foreach ($order->orderItems as $product)
        <tr>
            <td style="padding:15px;text-align:center;vertical-align:center;">
                {{ $product->quantity }} db
            </td>

            <td style="padding:7px;">
                @if(!empty($product->product->deleted_at))
                    TÖRÖLT KÖNYV:<br>
                @endif
                @if(isset($product->product->authors) && !empty($product->product->authors)){{ $product->product->authors }}:@endif <br>
                <b>{{ $product->product?->title }}</b><br>
                <div>ISBN: {{ $product->product?->isbn }}</div>
            </td>
            <td style="padding:7px;text-align:center;vertical-align:center;">
                {{ $product?->price }} Ft / db
            </td>
            <td style="padding:15px;text-align:center;vertical-align:center;">
                {{ $product?->total }} Ft
            </td>
        </tr>
    @endforeach

    <tr style="background-color:#f9fafb;">
        <td colspan="3" style="padding:15px">
            <div><b>@if($order->shippinMethond !== 'home') Átvétel: @else
                        Szállítás: @endif</b> {{ $order->shippingMethod->name }}
            </div>
        </td>
        <td style="padding:15px; text-align: center; vertical-align: center;">{{ $order->shipping_fee }} Ft</td>
    </tr>

    @if($order->payment_fee)
        <tr style="background-color:#f9fafb;">
            <td colspan="3" style="padding:15px"><b>Kényelmi költség:</b></td>
            <td style="padding:15px; text-align: center; vertical-align: center;">{{ $order->payment_fee }} Ft</td>
        </tr>
    @endif

    <tr style="background-color:#f9fafb;font-size:.9em">
        <td colspan="4" style="text-align:right;font-size:1.5em;padding:15px;">
            Összesen: {{ $order->total_amount }} Ft
        </td>
    </tr>

    <tr style="background-color:#f9fafb">
        <td colspan="3" style="text-align:right;padding:15px;">
            <div><b>Fizetés:</b></div>
        </td>
        <td colspan="" style="padding:15px;">
            <div>{{ $order->paymentMethod->name }}</div>
        </td>
    </tr>
    </tbody>
</table>
