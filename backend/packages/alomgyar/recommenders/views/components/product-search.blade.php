<div>
    <select class="form-control select-search @error($name) border-bottom-danger @enderror"
            data-placeholder="Válassz egyet..." name="{{ $name }}" wire:model="recommender.{{ $name }}">
        <option></option>
        @foreach($products as $product)
            <option value="{{ $product->id }}" @if($product->id === ($recommender->$name ?? 0)) selected @endif>
                @if($product->id === ($recommender->$name ?? 0)) selected @endif {{ $product->title }}
                ({{ $product->isbn }})
            </option>
        @endforeach
    </select>
</div>

@section('js')
    <script>
        $('.select-search').on('select2:select', function (e) {
            let data = e.params.data;
            Livewire.emit('setProductId', data.id);
        });
    </script>
@endsection
