<form wire:submit.prevent="save" id="form">
    <div class="row">
        <div class="col-md-8">

            <div class="card card-body border-top-1 border-top-success">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="col-form-label font-weight-bold" style="margin-bottom:20px;">
                                            Termék kiválasztás (ISBN, ID vagy NÉV):</label>
                                        <select class="form-control select-search" data-fouc
                                            data-placeholder="Válassz egyet...">
                                        </select>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label class="col-form-label font-weight-bold">Megrendelési lista
                                                    importálása</label>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="invalid-feedback"></div>
                                                <div class="dropzonefile dz-clickable @if ($importfile) dz-started @endif"
                                                    data-type="import" data-url="{{ route('file-upload') }}">
                                                    <div class="dz-message" data-dz-message="">
                                                        <span>Húzd ide az import file-t</span>
                                                    </div>
                                                    @if ($importfile)
                                                        <div
                                                            class="dz-preview dz-file-preview dz-processing dz-complete">
                                                            <div class="dz-image">
                                                                <img data-dz-thumbnail="">
                                                            </div>
                                                            <div class="dz-details">
                                                                <div class="dz-filename">
                                                                    <span data-dz-name="">{{ $importfile }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="dz-progress">
                                                                <span class="dz-upload"
                                                                    data-dz-uploadprogress=""></span>
                                                            </div>
                                                            <div class="dz-error-message">
                                                                <span data-dz-errormessage=""></span>
                                                            </div>
                                                            <div class="dz-success-mark">
                                                                <svg width="54px" height="54px" viewBox="0 0 54 54"
                                                                    version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                    <title>Check</title>
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <path
                                                                            d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                                                                            stroke-opacity="0.198794158"
                                                                            stroke="#747474" fill-opacity="0.816519475"
                                                                            fill="#FFFFFF"></path>
                                                                    </g>
                                                                </svg>
                                                            </div>
                                                            <div class="dz-error-mark">
                                                                <svg width="54px" height="54px" viewBox="0 0 54 54"
                                                                    version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                    <title>Error</title>
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <g stroke="#747474" stroke-opacity="0.198794158"
                                                                            fill="#FFFFFF" fill-opacity="0.816519475">
                                                                            <path
                                                                                d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z">
                                                                            </path>
                                                                        </g>
                                                                    </g>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <input type="hidden" name="importfile"
                                                    @if ($importfile) value="{{ $importfile }}" @endif>
                                            </div>
                                            <div class="col-lg-2">
                                                <a class="btn btn-outline-success legitRipple my-2" href="#"
                                                    @if (!$importfile) style="pointer-events: none;" @endif
                                                    wire:click="loadFromFile()">
                                                    Betöltés
                                                </a>
                                            </div>
                                            <div class="col-lg-2">
                                                <a href="/gephaz/storage/orders/minta.xlsx"
                                                    class="btn btn-outline-info legitRipple my-2">Minta fájl</a>
                                            </div>
                                        </div>
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
                                        <span
                                            class="form-text text-danger">{{ $errors->first('order.items.*.title') }}</span>
                                    </td>
                                    <td>{{ $item['tax_rate'] }}%
                                        <span
                                            class="form-text text-danger">{{ $errors->first('order.items.*.tax_rate') }}</span>
                                    </td>
                                    <td style="position: relative">
                                        @if (($edit_item['id'] ?? false) == $item['id'])
                                            <input wire:model="edit_item.price_sale" type="number" style="width:70px;"
                                                value="{{ $item['price_sale'] }}" class="form-control edit original">
                                        @else
                                            {{ $item['price_sale'] }}
                                        @endif
                                        <span
                                            class="form-text text-danger">{{ $errors->first('order.items.*.price_sale') }}</span>
                                    </td>
                                    <td style="white-space: nowrap">{{ $item['price_list'] }}
                                        {{ ($item['discount'] ?? null) > 0 ? '(' . $item['discount'] . '%)' : '' }}
                                        <span
                                            class="form-text text-danger">{{ $errors->first('order.items.*.price_list') }}</span>
                                    </td>
                                    <td>
                                        @if (($edit_item['id'] ?? false) == $item['id'])
                                            <input wire:model="edit_item.quantity" type="number" style="width:50px;"
                                                value="{{ $item['quantity'] }}" class="form-control edit">
                                        @else
                                            {{ $item['quantity'] }}
                                        @endif
                                        <span
                                            class="form-text text-danger">{{ $errors->first('order.items.*.quantity') }}</span>
                                    </td>
                                    <td><span class="font-weight-semibold">{{ $item['price_total'] }} Ft</span></td>
                                    <td class="text-right px-0" style="width:100px;">
                                        @if (($edit_item['id'] ?? false) == $item['id'])
                                            <a wire:click="saveItem({{ $item['id'] }})" class="btn btn-sm mx-0"><i
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
                                    <div class="font-weight-semibold h4 pt-5">{{ $order['total'] ?? 0 }} Ft</div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-md-4">

            <a wire:click.prevent="save" class="btn btn-lg d-block btn-success mb-2 text-white">ELKÜLD</a>

            <div class="card card-body">
                <div class="row">
                    <col-sm-12>
                        <div class="form-group" wire:ignore>
                            <label class="col-form-label font-weight-bold">
                                Ügyfél kiválasztása:</label>
                            <select class="form-control select-customer-search" data-fouc
                                data-placeholder="Válassz egyet...">
                            </select>
                        </div>
                    </col-sm-12>
                </div>
            </div>
            <div class="card">
                <div class="card-body pb-2">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="font-weight-bold" for="address">Fizetési mód</label>
                                <select class="form-control" wire:model="order.payment_method">
                                    @foreach (\Alomgyar\Methods\PaymentMethod::get() as $method)
                                        @if (in_array($method->method_id, ['card', 'cash']))
                                            <option value="{{ $method->method_id }}">{{ $method->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <div class="row">
                            <div class="form-group mb-3 mb-md-2">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <div class="uniform-choice">
                                            <span
                                                class="{{ ($order['address']['entity_type'] ?? 1) == 1 ? 'checked' : '' }}">
                                                <input type="radio" class="form-check-input-styled" data-fouc=""
                                                    value="1" wire:model="order.address.entity_type">
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
                                                <input type="radio" class="form-check-input-styled" value="2"
                                                    wire:model="order.address.entity_type">
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
                                    <label class="font-weight-bold" for="vat_number">Cég név</label>
                                    <input type="text" class="form-control"
                                        wire:model="order.address.business_name" id="business_name">
                                    <span
                                        class="form-text text-danger">{{ $order['addressError']['business_name'] ?? false }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="font-weight-bold" for="vat_number">Adószám</label>
                                <input type="text"
                                    class="form-control @if ($order['addressErrorX']['vat_number'] ?? false) border-danger @endif"
                                    wire:model="order.address.vat_number" id="vat_number">
                                <span
                                    class="form-text text-danger">{{ $order['addressError']['vat_number'] ?? false }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="font-weight-bold" for="lastname">Vezetéknév</label>
                                <input type="text" class="form-control" wire:model="order.address.last_name"
                                    id="lastname">
                                <span
                                    class="form-text text-danger">{{ $errors->first('order.address.last_name') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="font-weight-bold" for="firstname">Keresztnév</label>
                                <input type="text" class="form-control" wire:model="order.address.first_name"
                                    id="firstname">
                                <span
                                    class="form-text text-danger">{{ $errors->first('order.address.first_name') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="font-weight-bold" for="email">Email</label>
                                <input type="email" class="form-control" wire:model="order.email" id="email">
                                <span class="form-text text-danger">{{ $errors->first('order.email') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="font-weight-bold" for="phone">Telefonszám</label>
                                <input type="text" class="form-control" wire:model="order.phone" id="phone">
                                <span class="form-text text-danger">{{ $errors->first('order.phone') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="font-weight-bold">Szállítási Mód</label>
                                <select class="form-control" wire:model="order.shipping_method">
                                    @foreach (\Alomgyar\Methods\ShippingMethod::get() as $method)
                                        <option value="{{ $method->method_id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="font-weight-bold" for="address">Cím</label>
                                <input type="text" class="form-control" wire:model="order.address.address"
                                    id="address">
                                <span
                                    class="form-text text-danger">{{ $order['addressError']['address'] ?? false }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="font-weight-bold" for="zip_code">Irányítószám</label>
                                <input type="text" class="form-control" wire:model="order.address.zip_code"
                                    id="zip_code">
                                <span
                                    class="form-text text-danger">{{ $order['addressError']['zip_code'] ?? false }}</span>
                            </div>

                            <div class="col-sm-4">
                                <label class="font-weight-bold" for="city">Város</label>
                                <input type="text" class="form-control" wire:model="order.address.city"
                                    id="city">
                                <span
                                    class="form-text text-danger">{{ $order['addressError']['city'] ?? false }}</span>
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
</form>

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

                $('.select-customer-search').select2({
                    ajax: {
                        url: '{{ route('customers.search') }}',
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
                    placeholder: 'Válassz egy...',
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

        $('.select-customer-search').on('select2:select', function(e) {
            let data = e.params.data;
            Livewire.emit('setCustomerID', data.id);
        });
        window.addEventListener('restartSelect2', event => {
            Select2Selects.init();
        });

        Dropzone.autoDiscover = false;
        $("div.dropzonefile").each(function() {
            const element = $(this);
            $(this).dropzone({
                paramName: "file",
                url: $(this).data('url'),
                //acceptedFiles: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                maxFilesize: 10,
                thumbnailWidth: 350,
                thumbnailHeight: 350,
                uploadMultiple: false,
                //previewTemplate: document.querySelector("#tpl").innerHTML,
                dictInvalidFileType: 'Csak xls tölthető fel',
                dictFileTooBig: 'Az xls mérete nem lehet több, mint 10 Mb',
                params: {
                    _token: $('input[name="_token"]').val(),
                    type: element.data('type')
                },
                thumbnail: function(file, dataURL) {
                    //element.find('img.preview').data('preview', dataURL);
                },
                error: function(file, response) {
                    console.log(response);
                    $('.invalid-feedback').html(response);
                    //$('#dropzone_image').after('<span class="invalid-feedback" role="alert" style="display: inline;"><strong>' + response + '</strong></span>')
                },
                sending: function() {
                    // loading($("div#entry_image_con"));
                },
                uploadprogress: function() {
                    //  $("#entry_image_con .fa-spinner").show();
                },
                success: function(file, response) {
                    // loadfinished($("div#entry_image_con"));
                    element.next('input').val(response.url);
                    Livewire.emit('setImportFile', response.url);
                    //element.find('img.preview').attr('src', element.find('img.preview').data('preview'));
                }
            });
        });
    </script>
@endsection
