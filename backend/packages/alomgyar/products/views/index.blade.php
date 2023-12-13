@extends('admin::layouts.master')
@section('pageTitle')
    Könyvek
@endsection

@section('header')
    @include('products::layouts.header', ['title' => 'Könyvek', 'subtitle' => 'Összes', 'button' => route('products.create')])
@endsection
@section('js')
<script>
// Basic slider
    teest = $('.ion').ionRangeSlider({
        type: 'double',
        min: 0,
        max: 100,
    // from: 20,
    // to: 80
    });

    $('.select2-cat').select2({
        minimumResultsForSearch: 20,
        placeholder: 'Összes kategória',
        allowClear: true
    });

    $('.select2-subcat').select2({
        minimumResultsForSearch: 20,
        placeholder: 'Összes alkategória',
        allowClear: true
    });
    $('.select2-vatrate').select2({
        minimumResultsForSearch: 20,
        placeholder: 'Áfa',
        allowClear: true
    });
    $('.select2-warehouse').select2({
        minimumResultsForSearch: 20,
        placeholder: 'Raktár',
        allowClear: true
    });

    window.addEventListener('listUpdated', event => {
        $('.select2').select2({
            minimumResultsForSearch: 20
        });
    });

    function handleSelect(that) {
        Livewire.emit('setFilter', {  [$(that).attr('name')] : $(that).val() } );
        // console.log( $(that).attr('name')+' : '+$(that).val());
    }
    //$( document ).ready(function() {
    //    $('.sidebar-main-toggle').click();
    //});
    const Select2Selects = function () {

        const _componentSelect2 = function () {
            if (!$().select2) {
                console.warn('Warning - select2.min.js is not loaded.');
                return;
            }

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

            $('.select-search').on('select2:select', function (e) {
                let data = e.params.data;
                Livewire.emit('setAuthorId', data.id);
            });

            $('.select-search').on('select2:unselect', function (e) {
                Livewire.emit('setAuthorId', false);
            });

            $('.select-search-supplier').select2({
                ajax: {
                    url: '{{ route('suppliers.search') }}',
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
                placeholder: 'Összes beszállító',
                allowClear: true
            });

            $('.select-search-supplier').on('select2:select', function (e) {
                let data = e.params.data;
                Livewire.emit('setSupplierId', data.id);
            });

            $('.select-search-supplier').on('select2:unselect', function (e) {
                Livewire.emit('setSupplierId', false);
            });
            $('.select-search-publisher').select2({
                ajax: {
                    url: '{{ route('publishers.search') }}',
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
                placeholder: 'Összes kiadó',
                allowClear: true
            });

            $('.select-search-publisher').on('select2:select', function (e) {
                let data = e.params.data;
                Livewire.emit('setPublisherId', data.id);
            });

            $('.select-search-publisher').on('select2:unselect', function (e) {
                Livewire.emit('setPublisherId', false);
            });
        };

        return {
            init: function () {
                $('.select2-cat').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Összes kategória',
                    allowClear: true
                });

                $('.select2-subcat').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Összes alkategória',
                    allowClear: true
                });
                $('.select2-taxrate').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Áfa',
                    allowClear: true
                });
                $('.select2-warehouse').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Raktár',
                    allowClear: true
                });
                $('.select2-supplier').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Beszállító',
                    allowClear: true
                });
                $('.select2-publisher').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Kiadó',
                    allowClear: true
                });
                _componentSelect2();
            }
        }
    }();

    document.addEventListener('DOMContentLoaded', function () {
        Select2Selects.init();
    });

    window.addEventListener('restartSelect2', event => {
        Select2Selects.init();
    })
</script>
@endsection
@section('css')
<style>
.select2-selection--single {
    margin-left: 20px!important;
}
</style>
@endsection
@section('content')

    @livewire('products::listcomponent')
    {{-- @livewire('products::cards') --}}

@endsection
