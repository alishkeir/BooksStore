@extends('templates::email.common')

@section('content')
    {!! $body !!}
@endsection

@if(isset($footerContent))
@section('$footerContent')
    {!! $footerContent !!}
@endsection
@endif
