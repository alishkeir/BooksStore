<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('pageTitle')@isset($pageTitle)
        -
        @endisset{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/global_assets/css/extras/animate.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/skvadmin.css') }}" rel="stylesheet" type="text/css">

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @yield('css')
    <!-- /global stylesheets -->
</head>

<body>

    @include('admin::layouts.navbar')

    <!-- Page content -->
    <div class="page-content">
        @include('admin::layouts.sidebar')

        <!-- Main content -->
        <div class="content-wrapper">


            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                @yield('header')
                <!-- /page header -->
                <div class="content">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>

            </div>

            <x-admin::toast-message />

            <x-admin::loader />

        </div>

        <div id="tpl">
            <div>

            </div>
        </div>

        <!-- Core JS files -->

        <script src="{{ asset('assets/admin/global_assets/js/main/jquery351.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/main/bootstrap453.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/ui/ripple.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/buttons/spin.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/buttons/ladda.min.js') }}"></script>
        <!-- /core JS files -->

        <script src="https://cdn.tiny.cloud/1/4ecjs2ojabfzkedttxjb9kplmnyoq9ym7x8q55xumu2j21a2/tinymce/5/tinymce.min.js"
            referrerpolicy="origin"></script>

        <!-- Theme JS files -->
        <script src="{{ asset('assets/admin/global_assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/form_inputs.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/editors/summernote/summernote.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/sliders/ion_rangeslider.min.js') }}"></script>

        <script src="{{ asset('assets/admin/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
        <script src="{{ asset('assets/admin/global_assets/js/plugins/forms/styling/switch.min.js') }}"></script>

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>

        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>

        <script src="{{ asset('assets/admin/js/app.js') }}"></script>
        {{--		<script src="{{ asset('assets/admin/js/formchanges.js')}}"></script> --}}
        <script src="{{ asset('assets/admin/global_assets/js/dashboard.js') }}"></script>
        <script src="{{ asset('assets/admin/js/custom.js') }}"></script>
        <script src="{{ asset('assets/admin/js/dropzone.js') }}"></script>
        <script>
            Dropzone.autoDiscover = false;
            $("div.dropzone").each(function() {
                var element = $(this);
                $(this).dropzone({
                    paramName: "file",
                    url: $(this).data('url'),
                    acceptedFiles: 'image/jpeg, image/png',
                    maxFilesize: 10,
                    thumbnailWidth: 350,
                    thumbnailHeight: 350,
                    uploadMultiple: false,
                    previewTemplate: document.querySelector("#tpl").innerHTML,
                    dictInvalidFileType: 'Csak jpg vagy png képet tölthet fel',
                    dictFileTooBig: 'A kép mérete nem lehet több, mint 10 Mb',
                    params: {
                        _token: $('input[name="_token"]').val(),
                        type: element.data('type')
                    },
                    thumbnail: function(file, dataURL) {
                        element.find('img.preview').data('preview', dataURL);
                    },
                    error: function(file, response) {
                        $('.invalid-feedback').remove();
                        $('#dropzone_image').after(
                            '<span class="invalid-feedback" role="alert" style="display: inline;"><strong>' +
                            response + '</strong></span>')
                    },
                    sending: function() {
                        // loading($("div#entry_image_con"));
                    },
                    uploadprogress: function() {
                        //  $("#entry_image_con .fa-spinner").show();
                    },
                    success: function(file, response) {
                        // loadfinished($("div#entry_image_con"));
                        element.next('input').val(response.url);
                        element.find('img.preview').attr('src', element.find('img.preview').data(
                            'preview'));
                    }
                });
            });
        </script>
        @yield('js')

        @stack('inline-js')

        <script>
            /**
             * Show feedback on forms
             */
            @if (Session::has('success'))
                new PNotify({
                    title: "{{ Session::get('success') }}",
                    icon: 'icon-checkmark3',
                    type: 'success'
                });
                @php
                    Session::forget('success');
                @endphp
            @endif

            @if (Session::has('error'))
                new PNotify({
                    title: "{{ Session::get('error') }}",
                    icon: 'icon-blocked',
                    type: 'error'
                });
                @php
                    Session::forget('error');
                @endphp
            @endif
        </script>
</body>

</html>
