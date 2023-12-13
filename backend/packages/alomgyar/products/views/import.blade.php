@extends('admin::layouts.master')
@section('pageTitle')
    Árazó
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
                    console.log(response);
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
                    //console.log(response.url);
                    //element.find('img.preview').attr('src', element.find('img.preview').data('preview'));
                }
            });
        });


    </script>
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Könyvek', 'subtitle' => 'Árazó'])
@endsection

@section('content')

    <div class="card card-body border-top-1 border-top-success">

        <form action="{{route('process-import')}}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group row">
                <div class="col-lg-3">
                    <h6 class="mb-0 font-weight-semibold">Import</h6>
                </div>
                <div class="col-lg-5">
                    <div class="invalid-feedback"></div>
                    <div class="dropzonefile" data-type="import" data-url="{{url(route('file-upload'))}}">
                        <div class="dz-message" data-dz-message
                             style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;">
                            <span>Húzd ide az import file-t</span></div>

                    </div>
                    <input type="hidden" name="importfile">
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-outline-success legitRipple my-2">Betöltés</button>
                </div>
            </div>
        </form>

    </div>
    @if($products ?? false)
        <div class="card card-body border-top-1 border-top-info">
            <div class="media align-items-center align-items-md-start flex-column flex-md-row">
                <a href="#" class="text-teal mr-md-3 mb-3 mb-md-0">
                    <i class="icon-question7 text-success-400 border-success-400 border-2 rounded-round p-2"></i>
                </a>

                <div class="media-body text-center text-md-left">
                    <h6 class="media-title font-weight-semibold">Ellenőrzés ({{ $count['good'] + $count['bad'] }})</h6>
                    @if($count['good'] > 0 && $count['bad'] == 0)
                        <span
                            class="text-success">Sikeresen betöltve {{$count['good'] ?? 0}} termék árfrissítésre</span>
                    @elseif($count['good'] > 0 && $count['bad'] > 0)
                        <span class="text-warning"><strong>{{$count['bad'] ?? 0}}</strong> termék felülvizsgálata szükséges,  <strong>{{$count['good'] ?? 0}}</strong> termék betöltése sikeres</span>
                    @else
                        <span class="text-danger">Nincs árfrissítésre alkalmas termék</span>
                    @endif
                </div>
                @if($count['good'] != 0)
                    <a href="javascript:$('#runimport').submit();"
                       class="btn bg-warning-400 ml-md-3 mt-3 mt-md-0 legitRipple">Kijelöltek árának frissítése</a>
                @endif
            </div>
            <p class="mb-3 text-muted"></p>

            <hr class="mt-0">

            <form action="{{route('run-import')}}" method="POST" id="runimport">
                @method('POST')
                @csrf
                <table class="table table-striped">
                    <tr>
                        <th>Isbn</th>
                        <th>Név</th>
                        <th>Álomgyár Listaár</th>
                        <th>Álomgyár Akciós</th>
                        <th>OK Listaár</th>
                        <th>OK Akciós</th>
                        <th>Nagyker Listaár</th>
                        <th>Nagyker Akciós</th>
                        <th>Eredmény</th>
                        <th></th>
                    </tr>
                    @foreach($products ?? [] as $product)
                        <tr @if($product['status'] == 1) class="border-left border-success"
                            @else class="border-left border-danger" @endif >
                            @foreach($product as $field => $f)
                                @if($field == 'id')
                                    <td><a target="blank_" href="/gephaz/products/{{$f}}/edit">{{$f}}</a></td>
                                @elseif($field == 'status')
                                    <td>
                                        <input name="p[{{$product['isbn']}}][i]" value="1" @if($f == 1) checked
                                               @else disabled @endif type="checkbox">
                                        @if($f == 1)
                                            {{--}}
                                            <input name="p[{{$product['isbn']}}][p_0]" value="{{$product['alomgyar']}}" type="hidden">

                                            @if($product['olcsokonyvek'] > 0)
                                            <input name="p[{{$product['isbn']}}][p_1]" value="{{$product['olcsokonyvek']}}" type="hidden">
                                            @endif
                                            @if($product['nagyker'] > 0)
                                            <input name="p[{{$product['isbn']}}][p_2]" value="{{$product['nagyker']}}" type="hidden">
                                            @endif
                                            --}}
                                            <input name="p[{{$product['isbn']}}][prices]"
                                                   value="{{$product['alomgyar_list']}}|{{$product['alomgyar_sale']}}|{{$product['olcsokonyvek_list']}}|{{$product['olcsokonyvek_sale']}}|{{$product['nagyker_list']}}|{{$product['nagyker_sale']}}"
                                                   type="hidden">
                                        @endif
                                    </td>
                                @elseif($field == 'resp')
                                    <td>
                                        @foreach($f as $message)
                                            <div
                                                class="badge badge-flat border-danger text-danger-600">{{$message}}</div>
                                        @endforeach
                                    </td>
                                @else
                                    <td>{{$f}}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </form>
        </div>
    @else
        {{--}}
        <div class="card card-body border-top-1 border-top-info">
            <h6 class="mb-0 font-weight-semibold text-center">Nincs megjeleníthető adat</h6>
        </div>--}}
    @endif
    @if($result ?? false)
        <div class="card card-body border-top-1 border-top-info">
            <div class="alert bg-success text-white alert-styled-left">
                Az árak frissítése <span class="font-weight-semibold"> összesen {{$result['total']}} terméknél</span>
                sikeresen megtörtént
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr>
                            <td>Álomgyár</td>
                            <td>{{$result['0']}} termék érintett</td>
                        </tr>
                        <tr>
                            <td>Olcsokönyvek</td>
                            <td>{{$result['1']}} termék érintett</td>
                        </tr>
                        <tr>
                            <td>Nagyker</td>
                            <td>{{$result['2']}} termék érintett</td>
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
