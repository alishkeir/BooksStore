<form wire:submit.prevent="save" method="POST" id="runimport">
    @method('POST')
    @csrf
    <div class="card card-body border-top-1 border-top-info">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 mb-3 mb-md-0">
                <i class="icon-question7 text-success-400 border-success-400 border-2 rounded-round p-2"></i>
            </a>

            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Ellenőrzés ({{ $counts['product'] }})</h6>
                @if ($counts['product'] > 0)
                    <span class="text-success">Sikeresen betöltve {{ $counts['product'] ?? 0 }} termék mozgatásra</span>
                @else
                    <span class="text-danger">Nincs mozgatásra alkalmas termék</span>
                @endif
            </div>

        </div>
        <p class="mb-3 text-muted"></p>

        <hr class="mt-0">
        <table class="table table-striped">
            <tr>
                <th>{{ $counts['product'] }} féle könyv összesen</th>
            </tr>
            <tr>
                <th>{{ $counts['quantity'] }} db termék összesen</th>
            </tr>
            <tr>
                <th>{{ $counts['price'] }} Ft bruttó összesen</th>
            </tr>
        </table>
        <hr class="mt-2">
        @if (count($counts['bad'] ?? []) > 0)
            <h3 class="text-warning"><strong>{{ count($counts['bad'] ?? 0) }}</strong> terméknél hiba a fájlban</h3>
            <table class="table table-striped">
                <tr>
                    <th>ISBN</th>
                    <th>Hiba az excel fájlban</th>
                </tr>
                @foreach ($counts['bad'] ?? [] as $bad)
                    <tr>
                        <th>{{ $bad['isbn'] }}</th>
                        <td>
                            @foreach ($bad['resp'] ?? [] as $resp)
                                {{ $resp }}
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
    @if ($this->counts['product'] > 0 && $this->warehouseId && $this->fulfillment && $this->paymentDue)
        <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right">
            <li>
                <button type="submit" class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple"
                    title="{{ __('messages.save') }}">
                    <i class="fab-icon-open icon-paperplane"></i>
                </button>
            </li>
        </ul>
    @endif
</form>
