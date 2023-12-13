<div>
    @section('pageTitle')
        Termék be/kivételezés
    @endsection
    @section('header')
        @include('admin::layouts.header', ['title' => 'Raktárkezelő', 'subtitle' => 'Be/kivételezés'])
    @endsection
    @if (!$bulkProducts && $product_id)
        <form wire:submit.prevent="save">
    @endif
    <div class="row" style="flex-direction: row-reverse">
        <div class="col-md-6">
            @if (!$product_id)
                <div class="card card-body border-top-1 border-top-success">
                    <form wire:submit.prevent="importProduct" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Tömeges termékmozgatás excel
                                    file-ból</label>
                            </div>
                            <div class="col-lg-7">
                                <div class="invalid-feedback"></div>
                                <div class="dropzonefile dz-clickable @if ($importfile) dz-started @endif"
                                    data-type="import" data-url="{{ route('file-upload') }}">
                                    <div class="dz-message" data-dz-message="">
                                        <span>Húzd ide az import file-t</span>
                                    </div>
                                    @if ($importfile)
                                        <div class="dz-preview dz-file-preview dz-processing dz-complete">
                                            <div class="dz-image">
                                                <img data-dz-thumbnail="">
                                            </div>
                                            <div class="dz-details">
                                                <div class="dz-size">
                                                    <span data-dz-size=""><strong>{{ $importfileSize }}</strong></span>
                                                </div>
                                                <div class="dz-filename">
                                                    <span data-dz-name="">{{ $importfile }}</span>
                                                </div>
                                            </div>
                                            <div class="dz-progress">
                                                <span class="dz-upload" data-dz-uploadprogress=""></span>
                                            </div>
                                            <div class="dz-error-message">
                                                <span data-dz-errormessage=""></span>
                                            </div>
                                            <div class="dz-success-mark">
                                                <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>Check</title>
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <path
                                                            d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                                                            stroke-opacity="0.198794158" stroke="#747474"
                                                            fill-opacity="0.816519475" fill="#FFFFFF"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="dz-error-mark">
                                                <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>Error</title>
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <g stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF"
                                                            fill-opacity="0.816519475">
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
                                <button type="submit" class="btn btn-outline-success legitRipple my-2"
                                    @if ($bulkProducts) disabled @endif
                                    @if (!$importfile) disabled @endif
                                    onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i>
                                    Betöltés
                                </button>
                            </div>
                            <div class="col-lg-2">
                                <a href="/gephaz/storage/product-movements/termekmozgato-minta.xlsx"
                                    class="btn btn-outline-info legitRipple my-2">Minta fájl</a>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            @if (!$bulkProducts)
                <div class="card card-body border-top-1 border-top-success">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="col-form-label font-weight-bold" style="margin-bottom:20px;">Egy
                                        termék kiválasztása (ISBN, ID vagy NÉV)</label>
                                    <select class="form-control select-search" data-fouc
                                        data-placeholder="Válassz egyet...">
                                        @if ($product_id)
                                            <option value="{{ $product_id }}" selected="selected">
                                                {{ $product->title }}
                                                ({{ $product->isbn }})
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- @if ($product_id) --}}
    <div class="
    @if (!$product_id) d-none @endif">
        @include('warehouses::partials.source-destination')
    </div>
    {{-- @endif --}}
    <div>
        @if ($product_id)
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
    </div>
    </form>
    @if ($bulkProducts || $badProducts ?? false)
        @include('warehouses::partials.process-import')
    @endif
</div>
@section('js')
    <script src="{{ asset('assets/admin/js/dropzone.js') }}"></script>
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
                    language: {
                        inputTooShort: function(args) {
                            return "2 vagy több karakter";
                        },
                        noResults: function() {
                            return "Nem található.";
                        },
                        searching: function() {
                            return "Keresés...";
                        }
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
        const Select2Warehouse = function() {
            const _componentSelect2Warehouse = function() {
                let product = $('.select-search').val();
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }

                $('.select-search-warehouse-sourceid').select2({
                    ajax: {
                        url: '{{ route('warehouses.product.search') }}',
                        dataType: 'json',
                        data: function(params) {
                            let query = {
                                q: params.term,
                                product: product,
                                page: params.page || 1
                            }

                            return query;
                        },
                        delay: 250, // wait 250 milliseconds before triggering the request
                        cache: true,
                    },
                    language: {
                        inputTooShort: function(args) {
                            return "2 vagy több karakter";
                        },
                        noResults: function() {
                            return "Nem található.";
                        },
                        searching: function() {
                            return "Keresés...";
                        }
                    },
                });

                $('.select-search-warehouse-sourceid').on('select2:select', function(e) {
                    let data = e.params.data;
                    Livewire.emit('setSourceId', data.id);
                });
            };

            return {
                init: function() {
                    _componentSelect2Warehouse();
                }
            }
        }();
        const Select2WarehouseDestination = function() {
            const _componentSelect2WarehouseDestination = function() {
                let product = $('.select-search').val();
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }

                $('.select-search-warehouse-destinationid').select2({
                    ajax: {
                        url: '{{ route('warehouses.product.search') }}',
                        dataType: 'json',
                        data: function(params) {
                            let query = {
                                q: params.term,
                                product: product,
                                page: params.page || 1
                            }

                            return query;
                        },
                        delay: 250, // wait 250 milliseconds before triggering the request
                        cache: true,
                    },
                    language: {
                        inputTooShort: function(args) {
                            return "2 vagy több karakter";
                        },
                        noResults: function() {
                            return "Nem található.";
                        },
                        searching: function() {
                            return "Keresés...";
                        }
                    },
                });

                $('.select-search-warehouse-destinationid').on('select2:select', function(e) {
                    let data = e.params.data;
                    Livewire.emit('setDestinationId', data.id);
                });
            };

            return {
                init: function() {
                    _componentSelect2WarehouseDestination();
                }
            }
        }();
        const Select2Supplier = function() {
            const _componentSelect2Supplier = function() {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }

                $('.select-search-supplier').select2({
                    ajax: {
                        url: '{{ route('suppliers.search') }}',
                        dataType: 'json',
                        data: function(params) {
                            let query = {
                                q: params.term,
                                page: params.page || 1
                            }

                            return query;
                        },
                        delay: 250, // wait 250 milliseconds before triggering the request
                        cache: true,
                    },
                    language: {
                        inputTooShort: function(args) {
                            return "2 vagy több karakter";
                        },
                        noResults: function() {
                            return "Nem található.";
                        },
                        searching: function() {
                            return "Keresés...";
                        }
                    },
                });
                $('.select-search-supplier').on('select2:select', function(e) {
                    let data = e.params.data;
                    Livewire.emit('setSupplierId', data.id);
                });
            };

            return {
                init: function() {
                    _componentSelect2Supplier();
                }
            }
        }();

        document.addEventListener('DOMContentLoaded', function() {
            Select2Selects.init();
            Select2Warehouse.init();
            Select2WarehouseDestination.init();
            Select2Supplier.init();
        });

        window.addEventListener('restartSelect2', event => {
            Select2Selects.init();
            Select2Warehouse.init();
            Select2WarehouseDestination.init();
            Select2Supplier.init();

        })

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
{{-- @push('inline-js')
    <script>
        $(document).ready(function() {
            $('.sourceId').select2();
            $('.sourceId').on('change', function(e) {
                @this.set('source_id', e.target.value);
            });
            $('.destinationId').select2();
            $('.destinationId').on('change', function(e) {
                @this.set('destination_id', e.target.value);
            });
        });
    </script>
@endpush --}}

@section('css')
    <style>
        .card .dropzonefile {
            background-color: #fcfcfc;
            border-color: #ddd;
            display: flex;
            flex-direction: column;
            position: relative;
            border: 2px dashed rgba(0, 0, 0, .125);
            min-height: 40px;
            min-width: 100%;
            background-color: #fff;
            padding: .3125rem;
            border-radius: .1875rem;
            margin-top: 10px;
        }

        .dz-preview>* {
            display: none
        }

        .dz-preview .dz-details {
            display: block;
            width: 100%;
        }

        .dz-started .dz-message {
            display: none !important;
        }

        .dz-message {
            line-height: unset;
        }
    </style>
@endsection
