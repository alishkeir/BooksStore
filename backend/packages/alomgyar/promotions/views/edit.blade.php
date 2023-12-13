@extends('admin::layouts.master')
@section('pageTitle')
    {{ $model->title }} szerkesztése
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.10/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('promotions::_formjs')
    <script>
        // Display time picker
        $('.daterange-time').daterangepicker({
            timePicker: true,
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-light',
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        });
        $('.daterange-time').data('daterangepicker').setStartDate('{{ $model->active_from }}');
        $('.daterange-time').data('daterangepicker').setEndDate('{{ $model->active_to }}');
    </script>
<script>


    $('.select2').select2({
        minimumResultsForSearch: 20
    });
    window.addEventListener('listUpdated', event => {
        $('.select2').select2({
            minimumResultsForSearch: 20
        });
    });

    function handleSelect(that) {
        Livewire.emit('setFilter', {  [$(that).attr('name')] : $(that).val() } );
        console.log( $(that).attr('name')+' : '+$(that).val());
    }
</script>

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
    @include('admin::layouts.header', ['title' => 'Akciók', 'subtitle' => 'Szerkesztés'])
@endsection

@section('content')

    @include('promotions::_form')

@endsection
