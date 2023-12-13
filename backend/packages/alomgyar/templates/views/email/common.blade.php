@extends(\Alomgyar\Templates\Services\MailTemplateService::getByStoreId($storeId))

@section('content')
    {!! $body !!}
@endsection
