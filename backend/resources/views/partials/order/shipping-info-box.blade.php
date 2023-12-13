{{--
    $shippingObject
    $name
    $county
    $zip
    $city
    $address
    $phone
    --}}

<ul class="list-unstyled">

    @if ($shippingObject)
        <li>Átvételi hely: {{ $shippingObject->getProviderName() }}</li>
        <li>Átvételi hely azonosító:
            {{ $shippingObject->getProviderId() ?? '-' }}
        </li>
    @endif
    <li>{{ $name ?? null }}</li>
    <li>{{ $county ?? null }}</li>
    <li>{{ $zip ?? ($zip_code ?? null) }}
        {{ $city ?? null }}</li>
    <li>Cím: {{ $address ?? null }}</li>
    <li>Tel: {{ $phone ?? null }}</li>
</ul>
