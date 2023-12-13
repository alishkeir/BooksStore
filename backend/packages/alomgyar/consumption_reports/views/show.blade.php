@extends('admin::layouts.master')
@section('pageTitle')
    Fogyásjelentés
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Fogyásjelentés', 'subtitle' => 'Aktuális'])
@endsection

@section('content')
    @livewire('consumption_reports::consumption-report')
@endsection

@section('js')
    <script>
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
            };

            return {
                init: function() {
                    _componentSelect2Supplier();
                }
            }
        }();


        document.addEventListener('DOMContentLoaded', function() {
            Select2Supplier.init();
        });

        window.addEventListener('restartSelect2', event => {
            Select2Supplier.init();
        })
    </script>
@endsection
