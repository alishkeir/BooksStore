@extends('admin::layouts.master')
@section('pageTitle')
    Statisztikák
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Statisztikák', 'subtitle' => ''])
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Forgalmi statisztika</h5>
            </div>
            <div class="card-body">
                <form action="{{route('statistics.generate')}}" method="POST">
                    @csrf
                    <fieldset class="mb-3">

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Lekérdezés típusa</label>
                            <div class="col-lg-9">
                                <select class="form-control pl-2 select select2" name="type">
                                    <option value="1">Forgalom</option>
                                    <option value="2">Legtöbbet eladott könyv</option>
                                    <option value="3">Legtöbbet előjegyzett könyv</option>
                                </select>
                            </div>
                        </div>
                        <h3 class="text-uppercase font-size-sm font-weight-bold border-bottom mt-5">Időszak</h3>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Időszak</label>
                            <div class="col-lg-3">
                                <select class="form-control pl-2 select select2" name="filter[period]" id="filter-period">
                                    <option value="">Teljes időszak</option>
                                    <option value="m1" selected="selected">Elmúlt 1 hónap</option>
                                    <option value="m2">Elmúlt 2 hónap</option>
                                    <option value="m3">Elmúlt 3 hónap</option>
                                    <option value="m6">Elmúlt fél év</option>
                                    <option value="m12">Elmúlt 1 év</option>
                                    <option value="i">Egyedi</option>
                                </select>
                            </div>
                            <div class="col-lg-6" id="statistic-time-container" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="datetime-local" class="form-control" name="filter[from]">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="datetime-local" class="form-control" name="filter[to]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="text-uppercase font-size-sm font-weight-bold border-bottom mt-5">Forrás</h3>
                        <div class="form-group row">
                            <div class="col-md-4 pt-2">
                                <label class="form-check-label">
                                    <div>
                                        <span>
                                            <input type="checkbox" name="filter[is_shop]" value="1" />
                                        </span>
                                        Csak bolti eladás
                                    </div>
                                </label>
                                <label class="form-check-label">
                                    <div>
                                        <span>
                                            <input type="checkbox" name="filter[is_webshop]" value="1" />
                                        </span>
                                        Csak webshop
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="col-form-label">Webshop</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="filter[store][]" value="0" class="form-check-input">
                                                    Álomgyár
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="filter[store][]" value="1" class="form-check-input">
                                                    Olcsókönyvek
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="filter[store][]" value="2" class="form-check-input">
                                                    Nagyker
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="col-form-label">Bolt</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select class="form-control pl-2 select select2" name="filter[shop]">
                                            <option value="">Összes</option>
                                            @foreach($shops as $model)
                                            <option value="{{ $model->id }}">{{ $model->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="text-uppercase font-size-sm font-weight-bold border-bottom mt-5">Szűrő</h3>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Fizetési mód</label>
                            <div class="col-lg-3">
                                <select class="form-control pl-2 select select2" name="filter[pament_method]">
                                    <option value="">Mindegyik</option>
                                    @foreach($paymentMethods as $model)
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-lg-1"></div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <label class="col-form-label col-lg-3">Típus</label>
                                    <div class="col-lg-9">
                                        <div class="col-lg-12">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" name="filter[type]" value="-1" class="form-check-input" checked="checked">
                                                    Mindkettő
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" name="filter[type]" value="0" class="form-check-input">
                                                    Könyv
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" name="filter[type]" value="1" class="form-check-input">
                                                    E-könyv
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary legitRipple">Mehet <i class="icon-file-excel ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Cikktörzs lekérdezés</h5>
            </div>
            <div class="card-body">
                <form action="{{route('statistics.generate-products')}}" method="POST">
                    @csrf
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Paraméterek</legend>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input type="search" class="form-control" placeholder="Keresés" name="filter[search]">
                                    <div class="form-control-feedback">
                                        <i class="icon-search4 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select class="form-control select select2-subcat"
                                            name="filter[subcategory]">
                                        <option></option>
                                        @foreach ($subcategories ?? [] as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-cube3 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select class="form-control select-search" data-fouc name="filter[author]">
                                        <option></option>
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-quill4 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <select class="form-control select select2-supplier"
                                            name="filter[supplier]">
                                        <option></option>
                                        @foreach ($suppliers ?? [] as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-truck text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="form-check-label">
                                        <div><span><input type="checkbox" name="filter[only_ebook]" value="1"></span> Csak
                                            e-könyvek
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input type="checkbox" name="filter[only_book]" value="1"></span> Csak
                                            könyvek
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-check-label">
                                        <div><span><input type="checkbox" name="filter[cart_price]"></span> Csak
                                            kosár árral
                                        </div>
                                    </label><br>
                                    <label class="form-check-label">
                                        <div><span><input type="checkbox" name="filter[active]" checked="checked"></span> Csak látható
                                        </div>
                                    </label><br><br>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary legitRipple">Mehet <i class="icon-file-excel ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
$(function() {
    $('#filter-period').on('change', function() {
        if ($(this).val() == 'i') {
            $('#statistic-time-container').fadeIn();
        } else {
            $('#statistic-time-container').fadeOut();
        }
    });

    $('.select2-subcat').select2({
        minimumResultsForSearch: 20,
        placeholder: 'Összes alkategória',
        allowClear: true
    });
    $('.select2-supplier').select2({
        minimumResultsForSearch: 20,
        placeholder: 'Összes beszállító',
        allowClear: true
    });
    $('.select-search').select2({
        ajax: {
            url: '{{ route('authors.search') }}',
            dataType: 'json',
            data: function (params) {
                let query = {
                    q: params.term,
                    page: params.page || 1
                }

                return query;
            },
            delay: 250, // wait 250 milliseconds before triggering the request
            cache: true
        },
        placeholder: 'Összes szerző',
        allowClear: true
    });
});
</script>
@endsection
@section('css')
<style>
.select2-selection--single {
    margin-left: 20px!important;
}
</style>
@endsection
