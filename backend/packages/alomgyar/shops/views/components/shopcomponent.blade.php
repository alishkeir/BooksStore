<div>
    @if (!auth()->user()->shop_id)
        <div class="flex-fill overflow-auto">
            <div class="card">
                <div class="card-body p-3">
                    <div class="alert bg-info text-white alert-styled-left">
                        <span class="font-weight-semibold">Nincs bolt kiválasztva vagy nincs megfelelő
                            jogosultság!</span> Kérlek rendelj hozzá a Felhasználóhoz egy aktív boltot a folytatáshoz.
                    </div>
                </div>
            </div>
        </div>
    @else
        @if ($status == 'new')
            <div class="row">
                <div class="col-md-8">

                    <div class="card card-body border-top-1 border-top-success">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <div class="col-lg-5">
                                                <label class="col-form-label font-weight-bold"
                                                    style="margin-bottom:20px;">
                                                    Termék kiválasztás (ISBN, ID vagy NÉV):</label>
                                                <select @class([
                                                    'form-control select-search',
                                                    'border-danger' => $errors->has('order.items'),
                                                ]) data-fouc
                                                    data-placeholder="Válassz egyet...">
                                                </select>
                                                @error('order.items')
                                                    <span class="form-text text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3">
                                                <label class="col-form-label font-weight-bold"
                                                    style="margin-bottom:20px;">
                                                    Hozzáadás ISBN beolvasásból:</label>
                                                <input id="addFromIsbn" wire:model="addFromIsbn" class="form-control">
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="col-form-label font-weight-bold"
                                                    style="margin-bottom:20px;">
                                                    Alap kedvezmény:</label>
                                                <input wire:model="baseDiscount" placeholder="%"
                                                    value="{{ $baseDiscount ?? null }}" class="form-control">
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="col-form-label font-weight-bold"
                                                    style="margin-bottom:20px;">
                                                    Fix áron:</label>
                                                <input wire:model="fixedPrice" placeholder="HUF"
                                                    value="{{ $fixedPrice ?? null }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="bg-primary-300">
                                    <tr>
                                        <th>Tételek</th>
                                        <th>Áfa</th>
                                        <th>Egységár</th>
                                        <th>Listaár</th>
                                        <th>db</th>
                                        <th>Összesen</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order['items'] as $key => $item)
                                        <tr>
                                            <td>
                                                <a href="/gephaz/products/{{ $key }}/edit" target="_blank"
                                                    class="mb-0 h5">
                                                    {{ $item['title'] }}
                                                </a>
                                            </td>
                                            <td>{{ $item['tax_rate'] }}%</td>
                                            <td style="position: relative">
                                                @if (($edit_item['id'] ?? false) == $item['id'])
                                                    <input wire:model="edit_item.price_sale" type="number"
                                                        style="width:70px;" value="{{ $item['price_sale'] }}"
                                                        class="form-control edit original">
                                                    <input wire:model="edit_item.discount"
                                                        value="{{ $item['discount'] }}" placeholder="%"
                                                        style="text-align:center;max-width: 30px; position: absolute; height:28px; left: 90px; background-color: #eeeded; top:calc(50% - 14px); "
                                                        class="form-control">
                                                @else
                                                    {{ $item['price_sale'] }}
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap">{{ $item['price_list'] }}
                                                {{ ($item['discount'] ?? null) > 0 ? '(' . $item['discount'] . '%)' : '' }}
                                            </td>
                                            <td>
                                                @if (($edit_item['id'] ?? false) == $item['id'])
                                                    <input wire:model="edit_item.quantity" type="number"
                                                        style="width:50px;" value="{{ $item['quantity'] }}"
                                                        class="form-control edit">
                                                @else
                                                    {{ $item['quantity'] }}
                                                @endif

                                            </td>
                                            <td><span class="font-weight-semibold">{{ $item['price_total'] }} Ft</span>
                                            </td>
                                            <td class="text-right px-0" style="width:100px;">
                                                @if (($edit_item['id'] ?? false) == $item['id'])
                                                    <a wire:click="saveItem({{ $item['id'] }})"
                                                        class="btn btn-sm mx-0"><i
                                                            class="icon text-success icon-check"></i></a>
                                                @else
                                                    <a wire:click="openItemForEdit({{ $item['id'] }})"
                                                        class="btn btn-sm mx-0"><i class="icon icon-gear"></i></a>
                                                @endif
                                                <a wire:click="deleteItem({{ $item['id'] }})" class="btn btn-sm"><i
                                                        class="icon icon-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td>
                                            <div class="h4 pt-5">Végösszeg</div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2">
                                            <div class="font-weight-semibold h4 pt-5">{{ $order['total'] ?? 0 }} Ft
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">

                    <a wire:click.prevent="save" class="btn btn-lg d-block btn-success mb-2 text-white">ELKÜLD</a>

                    <div class="card">
                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0 mt-3">
                            <li class="nav-item"><a href="#tab-receipt" wire:click.prevent="setTab('tab-receipt')"
                                    class="nav-link legitRipple {{ $tab === 'tab-receipt' ? 'active' : '' }}"
                                    data-toggle="tab">Nyugta</a></li>
                            <li class="nav-item"><a href="#tab-invoice" wire:click.prevent="setTab('tab-invoice')"
                                    class="nav-link legitRipple {{ $tab === 'tab-invoice' ? 'active' : '' }}"
                                    data-toggle="tab">Számla</a></li>
                        </ul>
                        <div class="card-body pb-2">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="font-weight-bold" for="payment_method">Fizetési mód</label>
                                        <select @class([
                                            'form-control',
                                            'border-danger' => $errors->has('order.payment_method'),
                                        ]) wire:model="order.payment_method">
                                            @foreach (\Alomgyar\Methods\PaymentMethod::get() as $method)
                                                @if (in_array($method->method_id, ['card', 'cash', 'transfer']))
                                                    <option value="{{ $method->method_id }}">{{ $method->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('order.payment_method')
                                            <span class="form-text text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content card-body pt-2 mb-0 mt-0 border-top-0">
                            <div class="tab-pane fade @if ($tab === 'tab-receipt') show active @endif"
                                id="tab-receipt">
                                <div class="card-body"></div>
                            </div>
                            <div class="tab-pane fade  @if ($tab === 'tab-invoice') show active @endif"
                                id="tab-invoice">
                                <div class="form-group col-6">
                                    <div class="row">
                                        <div class="form-group mb-3 mb-md-2">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <div class="uniform-choice">
                                                        <span
                                                            class="{{ ($order['address']['entity_type'] ?? 1) == 1 ? 'checked' : '' }}">
                                                            <input type="radio" class="form-check-input-styled"
                                                                data-fouc="" value="1"
                                                                wire:model="order.address.entity_type">
                                                        </span>
                                                    </div>
                                                    Magánszemély
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <div class="uniform-choice">
                                                        <span
                                                            class="{{ ($order['address']['entity_type'] ?? false) == 2 ? 'checked' : '' }}">
                                                            <input type="radio" class="form-check-input-styled"
                                                                value="2" wire:model="order.address.entity_type">
                                                        </span>
                                                    </div>
                                                    Szervezet
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (($order['address']['entity_type'] ?? false) && $order['address']['entity_type'] == 2)
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="font-weight-bold" for="business_name">Cég név</label>
                                                <input type="text" @class([
                                                    'form-control',
                                                    'border-danger' => $errors->has('order.address.business_name'),
                                                ])
                                                    wire:model="order.address.business_name" id="business_name">
                                                @error('order.address.business_name')
                                                    <span class="form-text text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="font-weight-bold" for="vat_number">Adószám</label>
                                            <input type="text" @class([
                                                'form-control',
                                                'border-danger' => $errors->has('order.address.vat_number'),
                                            ])
                                                wire:model="order.address.vat_number" id="vat_number">
                                            @error('order.address.vat_number')
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="font-weight-bold" for="last_name">Vezetéknév</label>
                                            <input type="text" @class([
                                                'form-control',
                                                'border-danger' => $errors->has('order.address.last_name'),
                                            ])
                                                wire:model="order.address.last_name" id="last_name">
                                            @error('order.address.last_name')
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="font-weight-bold" for="first_name">Keresztnév</label>
                                            <input type="text" @class([
                                                'form-control',
                                                'border-danger' => $errors->has('order.address.first_name'),
                                            ])
                                                wire:model="order.address.first_name" id="first_name">
                                            @error('order.address.first_name')
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="font-weight-bold" for="address">Cím</label>
                                            <input type="text" @class([
                                                'form-control',
                                                'border-danger' => $errors->has('order.address.address'),
                                            ])
                                                wire:model="order.address.address" id="address">
                                            @error('order.address.address')
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="font-weight-bold" for="zip_code">Irányítószám</label>
                                            <input type="text" @class([
                                                'form-control',
                                                'border-danger' => $errors->has('order.address.zip_code'),
                                            ])
                                                wire:model="order.address.zip_code" id="zip_code">
                                            @error('order.address.zip_code')
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="font-weight-bold" for="city">Város</label>
                                            <input type="text" @class([
                                                'form-control',
                                                'border-danger' => $errors->has('order.address.city'),
                                            ])
                                                wire:model="order.address.city" id="city">
                                            @error('order.address.city')
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="font-weight-bold" for="country">Ország</label>
                                            <select name="country_id" @class([
                                                'form-control',
                                                'border-danger' => $errors->has('order.address.country_id'),
                                            ]) id="country"
                                                wire:model="order.address.country_id">

                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        @if ($country->id == ($address['country_id'] ?? false)) selected @endif>
                                                        {{ $country->name }} ({{ $country->code }})</option>
                                                @endforeach

                                            </select>
                                            @error('order.address.country_id')
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @else
            {{-- CREATING SUCCESSFULL: --}}
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-body">
                        <iframe id="printf" name="printf"
                            src="/gephaz/orders/invoice/get/{{ $newOrder->attachments[0] ?? false }}"
                            style="min-height:80vh;"></iframe>
                    </div>
                </div>
                <div class="col-md-4">
                    {{-- <a wire:click.prevent="new" class="btn btn-lg d-block btn-success mb-2 text-white"> --}}
                    {{-- NEED PAGE RELOAD, BECAUSE IN CASE OF CREATING A NEW ONE --}}
                    {{-- SELECT2 SELECT EVENT IS JUST NOT WORKING... --}}
                    <a href="#" onclick="window.location.reload()"
                        class="btn btn-lg d-block btn-success mb-2 text-white">
                        ÚJ LÉTREHOZÁSA</a>
                    <a onClick="window.frames['printf'].focus(); window.frames['printf'].print();"
                        class="btn btn-lg d-block btn-info mb-2 text-white mt-2">NYOMTATÁS</a>
                    <a href="/gephaz/orders/{{ $newOrder->id }}/edit"
                        class="btn btn-lg d-block btn-info mb-2 text-white">Rendelés megnyitása</a>
                    <div class="card">
                        <div class="card-body text-center">
                            <i
                                class="icon-check spinning icon-2x text-success-400 border-success-400 border-3 rounded-round p-3 mb-3 mt-1"></i>
                            <h5 class="card-title">Sikeres rendelés felvitel!</h5>
                            <br>
                            <div class="d-flex justify-content-center">
                                {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($newOrder->order_number, 'C128') !!}
                            </div>
                            <br>
                            <p class="p-2">Rendelésszám: {{ $newOrder->order_number ?? false }}</p>
                            <p class="pb-2">Számlaszám: {{ $newOrder->invoice_url ?? false }}</p>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-primary-300">
                                        <tr>
                                            <th>Tételek</th>
                                            <th>Áfa</th>
                                            <th>Egységár</th>
                                            <th>Összesen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($newOrder->orderItems as $item)
                                            @if ($item->product ?? false)
                                                <tr>
                                                    <td>
                                                        <a href="/gephaz/products/{{ $item->product->id }}/edit"
                                                            target="_blank" class="mb-0">
                                                            {{ $item->product->title }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->product->tax_rate }}%</td>
                                                    <td><span class="font-weight-semibold">{{ $item->quantity }} x
                                                            {{ $item->price }}</span>
                                                        <s>({{ $item->original_price }})</s>
                                                    </td>
                                                    <td><span
                                                            class="font-weight-semibold">{{ $item->price * $item->quantity }}
                                                            Ft</span>
                                                        @if ($newOrder->status < \App\Order::STATUS_WAITING_FOR_SHIPPING)
                                                            <a href="#" class="float-right">x</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        <tr>
                                            <td>
                                                <div class="h4">Végösszeg</div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td><span class="font-weight-semibold h4">{{ $newOrder->total_amount }}
                                                    Ft</span></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
@endif
@endif
</div>

@section('js')
    <script>
        const Select2Selects = function() {

            const _componentSelect2 = function() {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }

                $('.select-search').select2({
                    ajax: {
                        url: '{{ route('products.search', ['onlyBooks' => true]) }}',
                        dataType: 'json',
                        data: function(params) {
                            let query = {
                                q: params.term,
                                page: params.page || 1
                            }

                            return query;
                        },
                        delay: 250, // wait 250 milliseconds before triggering the request
                        cache: true
                    },
                });
            };

            return {
                init: function() {
                    _componentSelect2();
                }
            }
        }();

        window.addEventListener('waitUntilAttachment', event => {
            Livewire.emit('notYetAttachment');
        })

        document.addEventListener('DOMContentLoaded', function() {
            Select2Selects.init();
        });
        $('.select-search').on('select2:select', function(e) {
            let data = e.params.data;
            Livewire.emit('setProductId', data.id);
        });
        window.addEventListener('restartSelect2', event => {
            Select2Selects.init();
        })
    </script>
    <script>
        $('#addFromIsbn').focus();
    </script>
    <style>
        .form-control.edit {
            border: 2px solid #64b5f6 !important;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 4px;
            padding-right: 4px;
        }
    </style>
@endsection
