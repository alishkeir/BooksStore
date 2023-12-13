<script>
    @if(Session::has('success'))
        new PNotify({
            title: "{{ Session::get('success') }}",
            icon: 'icon-checkmark3',
            type: 'success'
        });
        @php
            Session::forget('success');
        @endphp
    @endif


    @if(Session::has('error'))
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