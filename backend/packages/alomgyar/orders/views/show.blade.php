@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->order_number }} sz. rendelés megtekintése
@endsection

@section('css')
    <style>
    .table-lg td, .table-lg th {
        padding:5px;;
    }
    .page-title h4{
        font-size:35px;
        margin-top:-11px;
        margin-bottom:-11px;
    }
    </style>
@endsection
@section('js')
    @include('orders::_formjs')
    @isset($model->store)
        <script>
            $('[data-domain]').hide();
            $('[data-domain={{$model->store}}]').show();

            const Select2Product = function () {
                const _componentSelect2 = function () {
                    if (!$().select2) {
                        console.warn('Warning - select2.min.js is not loaded.');
                        return;
                    }

                    $('.select-product').select2({
                        ajax: {
                            url: '{{ route('products.search') }}',
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
                    });

                    $('.select-product').on('select2:select', function (e) {
                        let data = e.params.data;
                        Livewire.emit('addNewProduct', data.id);
                    });
                };

                return {
                    init: function () {
                        _componentSelect2();
                    }
                }
            }();

            document.addEventListener('DOMContentLoaded', function () {
                Select2Product.init();
            });

            window.addEventListener('restartSelect2', event => {
                Select2Product.init();
            })
        </script>
    @endisset
    <script>
        window.livewire.on('select2', () => {
            Select2Selects.init();
        });
    </script>
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => $model->order_number, 'subtitle' => ''])
@endsection

@section('content')
    @livewire('orderpage', ['orderNumber' => $model->order_number, 'onlyEbook' => $onlyEbook])
@endsection
