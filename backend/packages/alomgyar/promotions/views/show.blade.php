@extends('admin::layouts.master')
@section('pageTitle')
    Új Promotions létrehozása
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
        locale: {
            format: 'YYYY-MM-DD hh:mm:ss'
        }
    });
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
@endsection

@section('header')
    
@endsection

@section('content')

@if($model ?? false)

                @livewire('promotions::products', ['promotion_id'=>$model->id] )

@endif

@endsection
