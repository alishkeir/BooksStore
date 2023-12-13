@extends('admin::layouts.master')
@section('pageTitle')
    Leltárív Export
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Leltárív Export', 'subtitle' => ''])
@endsection

@section('css')
    <style>
        #loading {
            display: none;
        }

        .bar {
            width: 10px;
            height: 40px;
            background: #fff;
            display: inline-block;
            transform-origin: bottom center;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            /*   box-shadow:5px 10px 20px inset rgba(255,23,25.2); */
            animation: loader 1.2s linear infinite;
        }

        .bar1 {
            animation-delay: 0.1s;
        }

        .bar2 {
            animation-delay: 0.2s;
        }

        .bar3 {
            animation-delay: 0.3s;
        }

        .bar4 {
            animation-delay: 0.4s;
        }

        .bar5 {
            animation-delay: 0.5s;
        }

        .bar6 {
            animation-delay: 0.6s;
        }

        .bar7 {
            animation-delay: 0.7s;
        }

        .bar8 {
            animation-delay: 0.8s;
        }

        @keyframes loader {
            0% {
                transform: scaleY(0.1);
                background: ;
            }

            50% {
                transform: scaleY(1);
                background: #2096F3;
            }

            100% {
                transform: scaleY(0.1);
                background: transparent;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('inventory_export.download') }}" method="get">
                <div class="row form-group">
                    <div class="col-md-6">
                        <select name="warehouse_id" id="warehouse_id" class="form-control select" required>
                            <option></option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->shop?->title ?? $warehouse->title }}
                                </option>
                            @endforeach
                            <option value="-1">Az összes bolt</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary ml-4">Export</button>
                </div>
                <div id="loading">
                    <div class="d-flex justify-content-center align-items-center">
                        <h3>Betöltés</h3>
                        <div class="ml-4">
                            <div class="bar bar1"></div>
                            <div class="bar bar2"></div>
                            <div class="bar bar3"></div>
                            <div class="bar bar4"></div>
                            <div class="bar bar5"></div>
                            <div class="bar bar6"></div>
                            <div class="bar bar7"></div>
                            <div class="bar bar8"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
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

        $('form').on('submit', function(e) {
            $('#loading').fadeIn()
            setTimeout(() => {
                $('#loading').fadeOut()
            }, 7000)
        }).on();
    </script>
@endsection
