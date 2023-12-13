<div>
    @section('pageTitle')
        Kereskedői fogyásjelentés import
    @endsection
    @section('header')
        @include('admin::layouts.header', [
            'title' => 'Kereskedői fogyásjelentés import',
            'subtitle' => '',
            'button' => route('consumption_report.merchant'),
            'buttonText' => 'Lista',
        ])
    @endsection

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

                    <form wire:submit.prevent="importConsumptionReport" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="col-form-label font-weight-bold">Kereskedői fogyásjelentés
                                    importálás</label>
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
                                    @if ($counts) disabled @endif
                                    @if (!$importfile) disabled @endif
                                    onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i>
                                    Betöltés
                                </button>
                            </div>
                            <div class="col-lg-2">
                                <a href="/gephaz/storage/consumption-reports/minta.xlsx"
                                    class="btn btn-outline-info legitRipple my-2">Minta fájl</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">
                        <i class="icon-cabinet mr-2"></i>
                        Paraméterek
                    </h6>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold" style="margin-bottom:20px;">Kereskedői
                                raktár</label>
                        </div>
                        <div class="col-lg-8">
                            <select class="form-control select-search" data-fouc data-placeholder="Válassz egyet...">
                                @if ($warehouseId)
                                    <option value="{{ $warehouseId }}" selected="selected">{{ $warehouse->title }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Teljesítés dátuma</label>
                        </div>
                        <div class="col-lg-8">
                            <input wire:model.lazy="fulfillment" id="fulfillment" type="date"
                                class="form-control @error('fulfillment') border-danger @enderror">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Fizetési határidő</label>
                        </div>
                        <div class="col-lg-8">
                            <input wire:model.lazy="paymentDue" id="payment_due" type="date"
                                class="form-control @error('paymentDue') border-danger @enderror">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Számla megjegyzés</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea wire:model.defer="comment" id="comment" class="form-control @error('comment') border-danger @enderror"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Bizonylat dátuma</label>
                        </div>
                        <div class="col-lg-8">
                            <input wire:model.lazy="created_at" id="pm_date" type="date"
                                class="form-control @error('created_at') border-danger @enderror">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="col-form-label font-weight-bold">Számla generálása</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" wire:model.lazy="docType" value="invoice"
                                    id="invoice_check">
                                <label class="form-check-label" for="invoice_check">
                                    Számla
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" wire:model.lazy="docType" value="receipt"
                                    id="receipt_check">
                                <label class="form-check-label" for="receipt_check">
                                    Nyugta
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($counts['product']) && $counts['product'] > 0 ?? false)
        @include('consumption_reports::partials.process-import')
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
                        url: '{{ route('warehouses.search', ['onlyMerchant' => true]) }}',
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
                    placeholder: 'Válassz egy kereskedői raktárat',
                });

                $('.select-search').on('select2:select', function(e) {
                    let data = e.params.data;
                    Livewire.emit('setWarehouseId', data.id);
                });
            };

            return {
                init: function() {
                    _componentSelect2();
                }
            }
        }();

        document.addEventListener('DOMContentLoaded', function() {
            Select2Selects.init();
        });

        window.addEventListener('restartSelect2', event => {
            Select2Selects.init();
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
