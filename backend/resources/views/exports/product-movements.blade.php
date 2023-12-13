<table style="width: 100%;border-bottom: 1px solid #a3a3a3;">
    <tr>
        <td style="text-align: left"><strong>Publish and More Kft.</strong></td>
        <td style="text-align: right">Kapcsolat: +3616143476, webshop@alomgyar.hu</td>
    </tr>
    <tr>
        <td style="text-align: left">1137 Budapest, Pozsonyi út 10.</td>
        <td style="text-align: right">Adószám: 23845338-2-41</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
<div style="text-align: center"><h1>Raktári átadás</h1></div>
<table style="width: 100%">
    <tbody>
    <tr>
        <td><h3>Bizonylat sorszáma:</h3></td>
        <td style="text-align: right"><h3>{{ $productMovement->reference_nr }}</h3></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>
</table>
<hr>
<table style="width: 100%">
    <tbody>
    <tr style="border-bottom: 1px solid #a3a3a3;border-top: 1px solid #a3a3a3;">
        <td style="border-right: 1px solid #a3a3a3;">
            <strong>Forrás raktár:</strong><br>
            <div style="margin: 1rem">
                <h4>{{ $productMovement->source?->title }}</h4>
                <p>{{ $productMovement->source?->fullAddress ?? '' }}</p>
            </div>
        </td>
        <td>
            <strong>Cél raktár:</strong><br>
            <div style="margin-bottom: 0.5rem; padding-left: 2rem">
                @if ($productMovement->destination_type != 5)
                <h4>{{ $productMovement->destination?->title }}</h4>
                <p>{{ $productMovement->destination?->fullAddress ?? '' }}</p>
                @else
                <p>{{ $productMovement->comment_void ?? '' }}</p>
                @endif
            </div>
        </td>
    </tr>
    </tbody>
</table>
<hr>
<br>
<table style="width: 100%">
    <thead style="background: #9289aa; text-align: center">
    <tr>
        <th>Szállítási mód</th>
        <th style="text-align: center">Kelte</th>
        <th style="text-align: center">Teljesítés</th>
    </tr>
    </thead>
    <tbody>
    <tr style="text-align: center">
        <td></td>
        <td style="text-align: center">{{ $productMovement->created_at->format('Y. m. d') }}</td>
        <td style="text-align: center">{{ $productMovement->created_at->format('Y. m. d') }}</td>
    </tr>
    </tbody>
</table>
<br><br>
<table style="width: 100%">
    <thead style="background: #9289aa;">
    <tr>
        <th>Megnevezés</th>
        <th style="text-align: center">Termék kód</th>
        <th style="text-align: center">Mennyiség</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    @foreach($productMovement->productItems as $item)
        <tr>
            <td><strong>{{ $item->product?->title }}</strong></td>
            <td style="text-align: center">{{ $item->product?->isbn }}</td>
            <td style="text-align: right">{{ $item->stock_in > 0 ? $item->stock_in : $item->stock_out }} db</td>
        </tr>
    @endforeach
    </tbody>
</table>
@if ($productMovement->comment_bottom ?? false)
<div style="position: absolute; bottom: 2rem;">
    <h5>Megjegyzés:</h5>
    <p>{{ $productMovement->comment_bottom ?? '' }}</p>
</div>
@endif
