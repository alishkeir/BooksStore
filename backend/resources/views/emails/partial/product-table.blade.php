@if($cart)
    @php
        $btnColor = match ($cart->store) {
            1 => 'rgb(255 194 0)',
            2 => 'rgb(73, 113, 255)',
            default => '#E30613'

        }
    @endphp

    <table style='width:100%;margin-top:15px;'>
        <tbody style="background-color:#f9fafb;font-size:.9em;">
        @foreach ($cart->items as $item)
            <tr>
                <td style="padding:15px;text-align:center;vertical-align:center;">
                    {{ $item->quantity }} db
                </td>

                <td style="padding:7px;">

                    @if(isset($item->product->authors) && !empty(trim($item->product->authors))) {{ $item->product->authors }}: @endif<br>
                    <b>{{ $item->product->title ?? '' }}</b><br>
                    <div>ISBN: {{ $item->product->isbn ?? '' }}</div>
                </td>
                <td style="padding:7px;text-align:center;vertical-align:center;">
                    {{ $item->is_cart_price ? $item->product->price($cart->store)->price_cart : $item->product->price($cart->store)->price_sale }}
                    Ft / db
                </td>
                <td style="padding:15px;text-align:center;vertical-align:center;">
                    <a href="{{ \App\Helpers\CurrentStoreUrl::get($cart->store) . '/konyv/' . $item->product->slug }}"
                       style="text-decoration: none; color: white; padding: 5px; background-color: {{ $btnColor }};">Megnézem</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="text-align: center; padding-top: 25px;">
        <a href="{{ \App\Helpers\CurrentStoreUrl::get($cart->store) . '/kosar' }}"
           style="text-decoration: none; color: white; padding: 10px; background-color: {{ $btnColor }}; font-weight: bold;">Kosaram megtekintése</a>
    </div>
@endif
