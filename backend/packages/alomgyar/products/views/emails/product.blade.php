@extends(\Alomgyar\Templates\Services\MailTemplateService::getByStoreId($storeId))

@section('content')

    <div>
        {!! $contentBody !!}
    </div>

@endsection
