<script>
    const now = new Date();

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

        const _componentSelect2 = function() {
            if (!$().select2) {
                console.warn('Warning - select2.min.js is not loaded.');
                return;
            }

            $('.select-search').select2({
                ajax: {
                    url: '{{ route('authors.search') }}',
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
                placeholder: 'Összes szerző',
            });

            $('.select-search').on('select2:select', function(e) {
                let data = e.params.data;
                Livewire.emit('setAuthorId', data.id);
            });
        };

        return {
            init: function() {
                $('.select2-cat').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Összes kategória'
                });

                $('.select2-subcat').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Összes alkategória'
                });
                $('.select2-taxrate').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Áfa'
                });
                $('.select2-warehouse').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Raktár'
                });
                $('.select2-supplier').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Beszállító'
                });
                $('.select2-publisher').select2({
                    minimumResultsForSearch: 20,
                    placeholder: 'Kiadó'
                });
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

    // Handling form submit
    form.onsubmit = (e) => postData(form, e);

    // Create slug automagically
    const title = document.getElementById('title');
    title.onblur = () => {
        let slug = stringToSlug(title.value);
        if ($('#slug').val().length == 0 || stringToSlug($('#slug').val()) == slug) {
            $('#slug').val(slug);
        }
        if ($('#meta_title').val().length == 0 || $('#meta_title').val() == title.value) {
            $('#meta_title').val(title.value);
        }
    }

    function handlePercent(that) {
        let original = $(that).parent().parent().find('input.original').val();
        let percent = $(that).val();
        let sale = $(that).prev('input');

        if (percent > 0) {
            sale.val(Math.round(original - ((original / 100) * percent)));
        } else {
            sale.val(sale.data('value'));
        }
    }
    $(document).ready(function() {
        $('#publisher_select').select2();
    });
</script>
