@extends('templates::email.common')

@section('content')
    {!! $body !!}
@endsection

@section('footerContent')
    {{ $footerContent }}
@endsection
