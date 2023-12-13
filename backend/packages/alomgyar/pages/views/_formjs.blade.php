<script>
    const now = new Date();

    tinymce.init({
        selector: '#description',
        plugins: 'code table wordcount lists advlist',
        toolbar: 'undo redo styleselect bold italic alignleft aligncenter alignright bullist numlist outdent indent code',
        menubar: false,
        // extended_valid_elements : 'iframe[src|frameborder|style|scrolling|class|width|height|name|align|allowfullscreen]',
        // entity_encoding : 'raw',
        setup: function (editor) {
            editor.on('change', function (e) {
                let content = tinymce.activeEditor.getContent();
                tinymce.activeEditor.targetElm.value = content;
            });
        }
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
