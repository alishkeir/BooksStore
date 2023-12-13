@extends('admin::layouts.master')
@section('pageTitle')
    Felhasználók listája
@endsection
@section('header')
    @include('admin::layouts.header', ['title' => 'Felhasználók', 'subtitle' => '', 'button' => route('user.create')])
@endsection

@section('content')

@livewire('admin::user.listcomponent')

@endsection

@section('js')
@include('admin::partials._packagejs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>

$('.select2').select2({
    minimumResultsForSearch: -1
});

$(function(){
    $('#filterform input, #filterform select').change(function(){
        $('#filterform').submit();
    })
    $('.sorting').click(function(){
        $('#sortby').val($(this).data('sort'));
        $('#order').val($('#order').val() == 'desc' ? 'asc' : 'desc');
        $('#filterform').submit();
    })
})
</script>
@endsection