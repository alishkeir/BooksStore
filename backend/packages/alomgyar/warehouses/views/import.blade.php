@extends('admin::layouts.master')
@section('pageTitle')
    Leltár import
@endsection

@section('js')
    <script src="{{ asset('assets/admin/js/dropzone.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;
        $("div.dropzonefile").each(function () {
            var element = $(this);
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
                thumbnail: function (file, dataURL) {
                    //element.find('img.preview').data('preview', dataURL);
                },
                error: function (file, response) {
                    // console.log(response);
                    $('.invalid-feedback').html(response);
                    //$('#dropzone_image').after('<span class="invalid-feedback" role="alert" style="display: inline;"><strong>' + response + '</strong></span>')
                },
                sending: function () {
                    // loading($("div#entry_image_con"));
                },
                uploadprogress: function () {
                    //  $("#entry_image_con .fa-spinner").show();
                },
                success: function (file, response) {
                    // loadfinished($("div#entry_image_con"));
                    element.next('input').val(response.url);
                    window.dispatchEvent(new CustomEvent('toast-message', {detail: {message: 'File feltöltése sikeres'}}));
                    //element.find('img.preview').attr('src', element.find('img.preview').data('preview'));
                }
            });
        });


    </script>
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Raktárak', 'subtitle' => 'Leltár import'])
@endsection

@section('content')
    @if(!isset($result) || !$result ?? TRUE)
    <div class="card card-body border-top-1 border-top-success">

        <form action="{{route('warehouses.process-import')}}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group row">
                <div class="col-lg-3">
                    <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                    <h6 class="mb-0 font-weight-semibold">{{ $warehouse->title }} leltár frissítése</h6>
                </div>
                <div class="col-lg-5">
                    <div class="invalid-feedback"></div>
                    <div class="dropzonefile" data-type="import" data-url="{{url(route('file-upload'))}}">
                        <div class="dz-message" data-dz-message
                             style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;">
                            <span>Húzd ide az import file-t</span></div>

                    </div>
                    <input type="hidden" name="importfile" @if(isset($importfile)) value="{{ $importfile }}" @endif>
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-outline-success legitRipple my-2">Betöltés</button>
                </div>
            </div>
        </form>

    </div>
    @endif
    @if($products ?? FALSE)
        <div class="card card-body border-top-1 border-top-info">
            <div class="media align-items-center align-items-md-start flex-column flex-md-row">
                <a href="#" class="text-teal mr-md-3 mb-3 mb-md-0">
                    <i class="icon-question7 text-success-400 border-success-400 border-2 rounded-round p-2"></i>
                </a>

                <div class="media-body text-center text-md-left">
                    <h6 class="media-title font-weight-semibold">Ellenőrzés ({{ $count['good'] + $count['bad'] }})</h6>
                    @if($count['good'] > 0 && $count['bad'] == 0)
                        <span
                            class="text-success">Sikeresen betöltve {{$count['good'] ?? 0}} termék leltár frissítésre</span>
                    @elseif($count['good'] > 0 && $count['bad'] > 0)
                        <span class="text-warning"><strong>{{$count['bad'] ?? 0}}</strong> termék felülvizsgálata szükséges,  <strong>{{$count['good'] ?? 0}}</strong> termék betöltése sikeres</span>
                    @else
                        <span class="text-danger">Nincs leltár frissítésre alkalmas termék</span>
                    @endif
                </div>
                @if($count['good'] != 0)
                    <a href="javascript:$('#runimport').submit();"
                       class="btn bg-warning-400 ml-md-3 mt-3 mt-md-0 legitRipple">Kijelöltek darabszámának
                        frissítése</a>
                @endif
            </div>
            <p class="mb-3 text-muted"></p>

            <hr class="mt-0">

            <form action="{{route('warehouses.run-import')}}" method="POST" id="runimport">
                @method('POST')
                @csrf
                <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                <h3>{{ $warehouse->title }} leltár frissítése</h3>
                <table class="table table-striped">
                    <tr>
                        <th>ISBN</th>
                        <th>Cím</th>
                        <th>Eredeti mennyiség a raktárban</th>
                        <th>Eredeti összes mennyiség</th>
                        <th>Új mennyiség a raktárban</th>
                        <th>Eredmény</th>
                        <th></th>
                    </tr>
                    @foreach($products ?? [] as $product)
                        <tr @if($product['status'] == 1) class="border-left border-success"
                            @else class="border-left border-danger" @endif >
                            @foreach($product as $field => $f)
                                @if($field == 'id')
                                    {{--<td><a target="blank_" href="/gephaz/products/{{$f}}/edit">{{$f}}</a></td>--}}
                                    <input name="p[{{$product['isbn']}}][id]"
                                           value="{{$f}}"
                                           type="hidden">
                                @else
                                    @if($field == 'status')
                                        <td>
                                            <input name="p[{{ $product['isbn'] }}][i]" value="1" @if($f == 1) checked
                                                   @else disabled @endif type="checkbox">
                                            @if($f == 1)
                                                @if($product['new_stock_in_warehouse'] == $product['original_stock_in_warehouse'])
                                                    <input name="p[{{ $product['isbn'] }}][i]" value="1" type="checkbox">
                                                @else
                                                    <input name="p[{{ $product['isbn'] }}][new_stock_in_warehouse]"
                                                           value="{{ $product['new_stock_in_warehouse'] }}"
                                                           type="hidden">
                                                    <input name="p[{{ $product['isbn'] }}][original_stock_in_warehouse]"
                                                           value="{{ $product['original_stock_in_warehouse'] }}"
                                                           type="hidden">
                                                @endif
                                            @endif
                                        </td>
                                    @elseif($field == 'resp')
                                        <td>
                                            @if (empty($f))
                                                <div class="badge badge-flat border-success text-success-600">Nincs hiba</div>
                                            @else
                                                @foreach($f as $message)
                                                    <div class="badge badge-flat border-danger text-danger-600">{{$message}}</div>
                                                @endforeach
                                            @endif
                                        </td>
                                    @else
                                        <td>{{$f}}</td>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </form>
        </div>
    @endif
    @if($result ?? FALSE)
        <div class="card card-body border-top-1 border-top-info">
            <div class="alert bg-success text-white alert-styled-left">
                A leltár frissítése <span class="font-weight-semibold"> összesen {{ $result['total'] }} terméknél</span>
                sikeresen megtörtént
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr>
                            <td>Bevételezés</td>
                            <td>{{ count($result['stock_in']) }} termék érintett</td>
                        </tr>
                        <tr>
                            <td>Kivételezés</td>
                            <td>{{ count($result['stock_out']) }} termék érintett</td>
                        </tr>
                        <tr>
                            <td>Nem történt változás</td>
                            <td>{{ count($result['no_change']) }} termék érintett</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endif
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

        .dz-preview > * {
            display: none
        }

        .dz-preview .dz-details {
            display: block;
            width: 100%;
        }

        .dz-started .dz-message {
            display: none !important;
        }
    </style>
@endsection
