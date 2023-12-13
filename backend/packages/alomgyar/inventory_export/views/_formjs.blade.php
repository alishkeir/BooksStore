<script>
    const Select2Selects = function() {

        const _componentSelect2 = function() {
            if (!$().select2) {
                console.warn('Warning - select2.min.js is not loaded.');
                return;
            }

            $('.select-search').select2({
                ajax: {
                    url: '{{ route('products.search', ['onlyBooks' => true]) }}',
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
            });

            $('.select-search').on('select2:select', function(e) {
                let data = e.params.data;
                Livewire.emit('setProductId', data.id);
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
</script>
