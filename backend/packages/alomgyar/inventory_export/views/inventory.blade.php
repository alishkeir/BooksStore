@extends('admin::layouts.master')
@section('pageTitle')
    Leltár
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Leltár', 'subtitle' => ''])
@endsection

@section('content')
    @livewire('inventory_export::inventory-list', ['warehouseID' => $warehouseID])
@endsection

@section('js')
    <script>
        const Select2Selects = function() {

            // Select2 examples
            const _componentSelect2 = function() {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }

                $('.select').select2({
                    placeholder: "Válasszon boltot",
                    allowClear: true,
                    width: '100%',
                });

                $('.select').on('select2:select', function (e) {
                    let data = e.params.data;
                    Livewire.emit('setWarehouseID', data.id);
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
    </script>
@endsection
