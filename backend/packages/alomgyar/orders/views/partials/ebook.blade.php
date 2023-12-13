@php
    use Alomgyar\Templates\Services\TemplateContentService;$eBookBody = null;

    if ($order->has_ebook)
    {
        $eBookTemplate = TemplateContentService::create()->getTemplateContent('checkout_ebook', $order->store, true);
        $eBookBody = $eBookTemplate->description ?? null;
    }
@endphp


@if($eBookBody)
    <div>
        {!! $eBookBody !!}
    </div>
@endif
