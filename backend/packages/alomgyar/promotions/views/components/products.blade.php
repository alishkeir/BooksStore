<div>
    <div x-data="{ open: false }" class="row">
        <div class="col-md-12">

            <div x-show="open" class="row gap-2 mb-2">
                <div class="col-md-12 mb-4">
                    <label class="col-form-label font-weight-bold">Egy
                        termék kiválasztása (ISBN, ID vagy NÉV)</label>
                    <select id="product_id" class="form-control select-search" name="product_id" data-fouc
                        data-placeholder="Válassz egyet..."
                        @isset($model)
                            disabled
                        @endisset>
                        @isset($model)
                            <option value="{{ $model->product_id }}" selected="selected">{{ $model->product->title }}
                                ({{ $model->product->isbn }})
                            </option>
                        @endisset
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Kiválasztott termék</label>
                    @if ($selectedProduct)
                        <div> {{ $selectedProduct->title . ' (' . $selectedProduct->isbn . ')' }} </div>
                    @endif
                </div>
                <div class="col-md-2">
                    <label class="font-weight-bold">Álomgyár</label>
                    <input wire:model="alomgyarPrice" type="text" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="font-weight-bold">Olcsókönyvek</label>
                    <input wire:model="olcsokonyvekPrice" type="text" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="font-weight-bold">Nagyker</label>
                    <input wire:model="nagykerPrice" type="text" class="form-control">
                </div>
                <div class="col-md-2">
                    <div wire:click="addNewProductToList" class="btn btn-xs btn-success">
                        Hozzáadás
                    </div>
                </div>
                <div class="col-md-12">
                    @if ($errorMessage)
                        <div class="helper-text text-danger"> {{ $errorMessage }} </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="col-md-5">
            <h6 class="mb-0 font-weight-semibold mb-3">TERMÉKEK: {{ $count }}</h6>
        </div>
        <div class="col-md-3 text-right">
            <div x-on:click="open = ! open" class="btn btn-outline-success legitRipple">
                <span x-show="!open">
                    Új termék hozzáadása
                </span>
                <span x-show="open">
                    Bezárás
                </span>
            </div>
        </div>
        <div class="col-md-4 text-right">

            <button data-toggle="modal" data-target="#addto"
                title="Újra importálásnál csak a friss importban szereplő termékek maradnak meg" type="button"
                class="btn btn-outline-success legitRipple">Termék import</button>
        </div>
        <div class="col-md-12">

            @if (!$showSelectedProducts)
                <div class="card card-body">
                    <div class="row">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-teal-700">
                                    <th>#</th>
                                    <th>Kiválasztott termékek ({{ $count }})</th>
                                    <th>Ár</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selected as $product)
                                    @if ($loop->index < 20)
                                        <tr>
                                            <td>{{ $product->product->isbn }}</td>
                                            <td>{{ substr($product->product->title, 0, 60) }}</td>
                                            <td style="white-space: nowrap;">
                                                @if ($promotion->store_0 == 1 && isset($product->product->price(0)->price_list))
                                                    <span style="color:#e62934;">{{ $product->price_sale_0 }} /
                                                        <s>{{ $product->product->price(0)->price_list }}</s>
                                                        ({{ round(100 - $product->price_sale_0 / ($product->product->price(0)->price_list / 100)) }}%)
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap;">
                                                @if ($promotion->store_1 == 1 && isset($product->product->price(1)->price_list))
                                                    <span style="color:#fbc72e;">{{ $product->price_sale_1 }} /
                                                        <s>{{ $product->product->price(1)->price_list }}</s>
                                                        ({{ round(100 - $product->price_sale_1 / ($product->product->price(1)->price_list / 100)) }}%)</span>
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap;">
                                                @if ($promotion->store_2 == 1 && isset($product->product->price(2)->price_list))
                                                    <span style="color:#4971ff;">{{ $product->price_sale_2 }} /
                                                        <s>{{ $product->product->price(2)->price_list }}</s>
                                                        ({{ round(100 - $product->price_sale_2 / ($product->product->price(2)->price_list / 100)) }}%)</span>
                                                @endif
                                            </td>

                                            <td class="text-right" style="white-space: nowrap;">
                                                <a target="blank_"
                                                    href="{{ route('products.edit', ['product' => $product->product]) }}#price"
                                                    class="list-icons-document"><i class="icon-pencil7"></i></a>
                                                {{-- }} <a wire:click="selectProduct({{$product->product->id}})" href="javascript:;" class="list-icons-document "><i class="icon-diff-removed"></i></a> --}}
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <div wire:click="removeProduct({{ $product->product_id }})"
                                                    onclick="confirm('Biztos, hogy törölni szeretnéd?') || event.stopImmediatePropagation()"
                                                    class="list-icons-document cursor-pointer text-danger-800 border-danger-600">
                                                    <i class="icon-trash"></i>
                                                </div>

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>
                        </table>
                        @if ($count > 20)
                            <button wire:click="show()" type="button" class="btn btn-outline-success legitRipple"
                                onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Összes
                                betöltése</button>
                        @endif
                    </div>
                </div>
            @else
                <div class="card card-body">
                    <div class="row">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-teal-700">
                                    <th>#</th>
                                    <th>Kiválasztott termékek ({{ $count }})</th>
                                    <th>Ár</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selected as $product)
                                    <tr>
                                        <td>{{ $product->product->isbn }}</td>
                                        <td>{{ substr($product->product->title, 0, 60) }}</td>
                                        <td style="white-space: nowrap;">
                                            @if ($promotion->store_0 == 1 && isset($product->product->price(0)->price_list))
                                                <span style="color:#e62934;">{{ $product->price_sale_0 }} /
                                                    <s>{{ $product->product->price(0)->price_list }}</s>
                                                    ({{ round(100 - $product->price_sale_0 / ($product->product->price(0)->price_list / 100)) }}%)
                                                </span>
                                            @endif
                                        </td>
                                        <td style="white-space: nowrap;">
                                            @if ($promotion->store_1 == 1 && isset($product->product->price(1)->price_list))
                                                <span style="color:#fbc72e;">{{ $product->price_sale_1 }} /
                                                    <s>{{ $product->product->price(1)->price_list }}</s>
                                                    ({{ round(100 - $product->price_sale_1 / ($product->product->price(1)->price_list / 100)) }}%)</span>
                                            @endif
                                        </td>
                                        <td style="white-space: nowrap;">
                                            @if ($promotion->store_2 == 1 && isset($product->product->price(2)->price_list))
                                                <span style="color:#4971ff;">{{ $product->price_sale_2 }} /
                                                    <s>{{ $product->product->price(2)->price_list }}</s>
                                                    ({{ round(100 - $product->price_sale_2 / ($product->product->price(2)->price_list / 100)) }}%)</span>
                                            @endif
                                        </td>

                                        <td class="text-right" style="white-space: nowrap;">
                                            <a target="blank_"
                                                href="{{ route('products.edit', ['product' => $product->product]) }}#price"
                                                class="list-icons-document "><i class="icon-pencil7"></i></a>
                                            {{-- }} <a wire:click="selectProduct({{$product->product->id}})" href="javascript:;" class="list-icons-document "><i class="icon-diff-removed"></i></a> --}}
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <div wire:click="removeProduct({{ $product->product_id }})"
                                                onclick="confirm('Biztos, hogy törölni szeretnéd?') || event.stopImmediatePropagation()"
                                                class="list-icons-document cursor-pointer text-danger-800 border-danger-600">
                                                <i class="icon-trash"></i>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<style>
    .table td,
    .table th {
        padding: 10px 5px;
    }

    .row::-webkit-scrollbar,
    .leftie::-webkit-scrollbar {
        width: 5px;
    }

    /* Track */
    .row::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    .row::-webkit-scrollbar-thumb {
        background: #26a69a;
    }

    .jhn-control {
        background: rgba(255, 255, 255, 0.75);
        width: 50px;
        display: inline-block;
        padding: 2px;
        outline: none;
        border-width: 2px 0px;
    }

    li.page-item {
        display: none;
    }

    .page-item:first-child,
    .page-item:nth-Child(2),
    .page-item.disabled+.page-item,
    .page-item:last-child,
    .page-item.active {

        display: block;
    }
</style>

<style>
    .select2-selection--single {
        margin-left: 20px !important;
    }

    .table td,
    .table th {
        padding: 2px;
    }
</style>

@push('inline-js')
    <script>
        const ProductSelect2Selects = function() {

            const _componentSelect2 = function() {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }

                $('.select-search').select2({
                    ajax: {
                        url: '{{ route('products.search') }}',
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

                $('.select-search').on('select2:select', function(e) {
                    let data = e.params.data;
                    Livewire.emit('setProductId', data.id);
                });
            };

            return {
                init: function() {
                    _componentSelect2();
                }
            }
        }();

        document.addEventListener('DOMContentLoaded', function() {
            ProductSelect2Selects.init();
        });

        window.addEventListener('restartSelect2', event => {
            ProductSelect2Selects.init();
        })
    </script>
@endpush
