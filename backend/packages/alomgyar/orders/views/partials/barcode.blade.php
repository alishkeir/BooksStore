<div style="text-align: center;" class="d-flex justify-content-center">
    <img src="{{ env('BACKEND_URL') . \Illuminate\Support\Facades\Storage::url( 'barcode/' . $order->order_number . '.png') }}" alt="{{ $order->order_number }}" width="350">
</div>
