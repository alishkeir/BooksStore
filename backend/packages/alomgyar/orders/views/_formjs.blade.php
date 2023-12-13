<script src="{{ asset('assets/admin/global_assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('assets/admin/global_assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('assets/admin/global_assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('assets/admin/global_assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
<script>
    const now = new Date();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if ($('.pickadate').length > 0) {
        $('.pickadate').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd'
        });
    }

    $('body').on({
        click: function(e) {
            e.preventDefault();
            let id = $('#model_id').val();
            if (!$(this).hasClass('disabled') && $(this).data('status') != {{ $model->status }}) {
                $.post("{{ route('orders.setstatus') }}", {
                    id: $('#model_id').val(),
                    status: $(this).data('status'),
                    invoice: $(this).attr('data-invoice')
                }, function(response) {
                    console.log(response)
                    if (response[$('#model_id').val()] == true) {
                        location.reload();
                    }
                });
            }
        }
    }, '.status-link');

    $('.skvad-switcher').each(function() {
        var element = $(this);
        var switcher = element.data('switch_id');
        $(this).find('input').change(function() {
            element.find('label').removeClass('btn-primary').addClass('btn-secondary');
            $(this).next('label').removeClass('btn-secondary').addClass('btn-primary');
            $('#' + switcher).attr('data-invoice', $(this).val());
        });
    });

    if ($('.skvad-switcher').length > 0) {
        $('.skvad-switcher input:checked').change();
    }

    $('body').on({
        click: function(e) {
            e.preventDefault();
            $.post("{{ route('orders.setpaymentstatus') }}", {
                id: $('#model_id').val(),
                payment_date: $('#invoice_date').val()
            }, function(response) {
                if (response[$('#model_id').val()] == true) {
                    location.reload();
                }
            });
        }
    }, '#payment_set_payed')

    $('body').on({
        click: function(e) {
            e.preventDefault();
            $.post("{{ route('orders.setpaymentstatus') }}", {
                id: $('#model_id').val()
            }, function(response) {
                if (response[$('#model_id').val()] == true) {
                    location.reload();
                }
            });
        }
    }, '#payment_set_unpayed')

    $('body').on({
        click: function(e) {
            e.preventDefault();
            $.post("{{ route('orders.delete') }}", {
                id: $('#model_id').val(),
                status: $(this).data('status')
            }, function(response) {
                if (response[$('#model_id').val()] == true) {

                }
                location.reload();
            });
        }
    }, '.inactivate_order')

    $('#description').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
        disableDragAndDrop: true
    });


    const Select2Selects = function() {

        // Select2 examples
        const _componentSelect2 = function() {
            if (!$().select2) {
                console.warn('Warning - select2.min.js is not loaded.');
                return;
            }

            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            $('.select-search').select2();

            $('.select-fixed-multiple').select2({
                minimumResultsForSearch: Infinity,
                width: 400
            });

            $('.select-point').select2({
                ajax: {
                    url: '{{ route('orders.points.search') }}',
                    dataType: 'json',
                    data: function(params) {
                        let query = {
                            q: params.term,
                            page: params.page || 1
                        }

                        return query;
                    },
                    delay: 250, // wait 250 milliseconds before triggering the request
                    cache: true
                },
                placeholder: 'Válassza ki az átvételi pontot',
            });
            $('.select-point').on('select2:select', function(e) {
                let data = e.params.data;
                Livewire.emit('setBoxID', data.id);
            });
            // Tagging support
            $('.select-multiple-tags').select2({
                tags: true
            });
        };

        return {
            init: function() {
                _componentSelect2();
            }
        }
    }();

    const OrderScripts = function() {

        const appendScripts = function() {
            $('#invoice-storno').click(function(e) {
                e.preventDefault();
                $.post($(this).data('href'), {
                    id: $(this).data('id')
                }, function(response) {
                    if (response.success == true) {
                        location.reload();
                    }
                })
            })
            $('#invoice-revert').click(function(e) {
                e.preventDefault();
                $.post($(this).data('href'), {
                    id: $(this).data('id'),
                    'revert': true
                }, function(response) {
                    if (response.success == true) {
                        location.reload();
                    }
                })
            })
        }

        return {
            init: function() {
                appendScripts();
            }
        }
    }();

    document.addEventListener('DOMContentLoaded', function() {
        Select2Selects.init();
        OrderScripts.init();
    });

    // Handling form submit
    if (form) {
        form.onsubmit = (e) => postData(form, e);
    }

    // Create slug automagically
    const title = document.getElementById('title');
    if (title) {
        title.onblur = () => {
            let slug = stringToSlug(title.value);
            if ($('#slug').val().length == 0 || stringToSlug($('#slug').val()) == slug) {
                $('#slug').val(slug);
            }
            if ($('#meta_title').val().length == 0 || $('#meta_title').val() == title.value) {
                $('#meta_title').val(title.value);
            }
        }
    }
</script>
