<table style="border-top: 1px dashed #000; width: 190px;">
    <tr>
        <td style="height: 0px;">&nbsp;</td>
    </tr>
</table>
<table style="width: 190px; text-align:center; line-height: 12px;">
    <tr>
        <td style="min-height: 30px; vertical-align: center;">Publish and More Kft.</td>
    </tr>
    <tr>
        <td style="min-height: 30px; vertical-align: center;">{{ \Alomgyar\Shops\Shop::find( Auth::user()->shop_id )->zip_code ?? ''}} {{ \Alomgyar\Shops\Shop::find( Auth::user()->shop_id )->city ?? ''}}, {{ \Alomgyar\Shops\Shop::find( Auth::user()->shop_id )->address ?? ''}}</td>
    </tr>
    <tr>
        <td style="min-height: 30px; vertical-align: center;">23845338-2-41</td>
    </tr>
</table>
<table style="border-bottom: 1px dashed #000; width: 190px;">
    <tr>
        <td style="height: 0px;">&nbsp;</td>
    </tr>
</table>
<table style="width: 190px;">
    <tr>
        <td style="height: 0px;">&nbsp;</td>
    </tr>
</table>
<table style="width: 190px; line-height: 14px;">
    @foreach($items as $item)
    <tr>
        <td colspan="3">{{$item->product->title}}</td>
    </tr>
    <tr>
        <td style="text-align: right;">{{number_format($item->quantity, 2, ',', ' ')}} db</td>
        <td style="text-align: right;">{{number_format($item->price, 2, ',', ' ')}} Ft</td>
        <td style="text-align: right;">{{number_format($item->total, 2, ',', ' ')}} Ft</td>
    </tr>

    @endforeach
</table>
<table style="border-bottom: 1px dashed #000; width: 190px;">
    <tr>
        <td style="height: 0px;">&nbsp;</td>
    </tr>
</table>
<table style="width: 190px;">
    <tr>
        <td style="height: 0px;">&nbsp;</td>
    </tr>
</table>
<table style="width: 190px;">
    <tr>
        <td>FIZETENDŐ:</td>
        <td style="text-align: right;">{{number_format($order->total_amount, 2, ',', ' ')}} Ft</td>
    </tr>
    <tr>
        <td>{{$order->order_number}}</td>
        <td style="text-align: right;">{{date('Y.m.d')}}</td>
    </tr>
</table>
<table style="width: 190px;">
    <tr>
        <td style="height: 0px;">&nbsp;</td>
    </tr>
</table>
<table style="text-align: center; width: 190px;">
    <tr><td>Köszönjük, hogy nálunk vásárolt!</td></tr>
</table>
<table style="width: 190px;">
    <tr>
        <td style="height: 0px;">&nbsp;</td>
    </tr>
</table>
<table style="text-align: center; width: 190px; font-size: 6px;">
    <tr>
        <td>
            Adóügyi elszámolásra nem alkalmas!
        </td>
    </tr>
</table>
