<div style="text-align: center;">
    <b>Rendelés azonosító:</b> <br>
    <h2>
        <a href="{{ \App\Helpers\CurrentStoreUrl::get($order->store) . '/profil/rendeleseim' }}"
           style="font-weight: bold; color: red;">{{ $order->order_number }}</a>
    </h2>
</div>
