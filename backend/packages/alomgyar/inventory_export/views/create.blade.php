@extends('admin::layouts.master')
@section('pageTitle')
    Leltár
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('js')
    @include('inventory_export::_formjs')
    <script>
        $(document).ready(function() {
            $('#product_id').change(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('inventory_export.create_product.get-quantity') }}",
                    data: {
                        'warehouseId': {{ $warehouseID }},
                        'productId': $(this).val(),
                    },
                    success: function(response) {
                        $('#stock').val(response)
                    }
                });



            })
        })
    </script>
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Leltár', 'subtitle' => __('general.add-new')])
@endsection

@section('content')
    @include('inventory_export::_form')
@endsection
