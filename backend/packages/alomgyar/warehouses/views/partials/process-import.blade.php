<form wire:submit.prevent="save" method="POST" id="runimport">
    @method('POST')
    @csrf
    <div class="card card-body border-top-1 border-top-info">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 mb-3 mb-md-0">
                <i class="icon-question7 text-success-400 border-success-400 border-2 rounded-round p-2"></i>
            </a>

            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Ellenőrzés
                    ({{ $count['good'] + $count['bad'] }})</h6>
                @if($count['good'] > 0 && $count['bad'] == 0)
                    <span class="text-success">Sikeresen betöltve {{$count['good'] ?? 0}} termék mozgatásra</span>
                @elseif($count['good'] > 0 && $count['bad'] > 0)
                    <span class="text-success"><strong>{{$this->count['good'] ?? 0}}</strong> termék betöltése sikeres, </span>
                    <span class="text-warning">az alábbi <strong>{{$count['bad'] ?? 0}}</strong> termék nem kerül importálásra</span>
                @else
                    <span class="text-danger">Nincs mozgatásra alkalmas termék</span>
                @endif
            </div>

        </div>
        <p class="mb-3 text-muted"></p>

        <hr class="mt-0">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>Isbn</th>
                <th>Név</th>
                <th>Mennyiség</th>
                <th>Eredmény</th>
                <th></th>
            </tr>
            @foreach($badProducts ?? [] as $key => $product)
                <tr @if($product['importable'] == 1) class="border-left border-success"
                    @else class="border-left border-danger" @endif >
                    @foreach($product as $field => $f)
                        @if($field == 'product_id')
                            <td>
                                <a target="_blank" href="/gephaz/products/{{$f}}/edit">{{$f}}</a>
                            </td>
                        @elseif($field == 'resp')
                            <td>
                                @foreach($f as $message)
                                    <div class="badge badge-flat border-danger text-danger-600">{{ $message }}</div>
                                    @if($message === 'Nincs ilyen termék')
                                        <a href="{{ route('products.create') }}" class="badge badge-flat badge-icon border-success text-success-600 rounded-circle ml-2" target="_blank">
                                            <i class="icon-plus22"></i>
                                        </a>
                                    @endif
                                @endforeach
                            </td>
                        @else
                            @if(!in_array($field, ['status', 'importable', 'stock_out', 'importing', 'id']))
                                <td>{{ $f }}</td>
                            @endif
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>
    @if($this->count['good'] > 0)
        @include('warehouses::partials.source-destination')
        <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right">
            <li>
                <button type="submit"
                        class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple"
                        title="{{ __('messages.save') }}">
                    <i class="fab-icon-open icon-paperplane"></i>
                </button>
            </li>
        </ul>
    @endif
</form>

