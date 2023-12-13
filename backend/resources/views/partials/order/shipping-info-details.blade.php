{{--
    $lastName
    $firstName
    $vatNumber
    $countryName
    $zipCode
    $city
    $address
    $comment
    --}}

<ul class="list-unstyled">
    <li>{{ $lastName ?? '-' }}
        {{ $firstName ?? '-' }}</li>
    @if ($vatNumber ?? false)
        <li>Adószám: {{ $vatNumber }}</li>
    @endif
    <li>{{ $countryName ?? '-' }}</li>
    <li>{{ $zipCode ?? '-' }}, {{ $city ?? '-' }}
    </li>
    <li>Cím: <br>{{ $address ?? '-' }}</li>
    <li>Megjegyzés:
        <br>{{ $comment ?? '' }}
    </li>
</ul>
