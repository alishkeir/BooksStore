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

    document.addEventListener('DOMContentLoaded', function() {
        Select2Selects.init();
    });

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
</script>
