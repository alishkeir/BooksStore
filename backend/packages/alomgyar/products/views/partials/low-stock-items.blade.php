<ul>
    @foreach($products as $item)
    <li>
        {{ $item->title }} (ISBN: {{ $item->isbn }}): {{ $item->stock }} db
    </li>
    @endforeach
</ul>