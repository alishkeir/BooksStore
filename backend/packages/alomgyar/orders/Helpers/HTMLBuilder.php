<?php

namespace Alomgyar\Orders\Helpers;

use Alomgyar\Templates\Services\TemplateContentService;
use App\Order;

class HTMLBuilder
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function buildOrderNumber(): string
    {
        return view('orders::partials.order-number', ['order' => $this->order])->render();
    }

    public function buildFollowCode(): string
    {
        return view('orders::partials.order-info', ['order' => $this->order])->render();
    }

    public function buildBarCode()
    {
        return view('orders::partials.barcode', ['order' => $this->order])->render();
    }

    public function buildProductTable(): string
    {
        return view('orders::partials.product-table', ['order' => $this->order])->render();
    }

    public function buildShippingInformaiton()
    {
        return view('orders::partials.shipping-information', ['order' => $this->order])->render();
    }

    public function buildBillingTable(): string
    {
        return view('orders::partials.billing-address', ['order' => $this->order])->render();
    }

    public function buildEBookOnlySection(): string
    {
        return view('orders::partials.ebook', ['order' => $this->order])->render();
    }

    public function buildTransferInformation()
    {
        $template = TemplateContentService::create()->getTemplateContent('transfer_info');

        return $this->order->paymentMethod->method_id === 'transfer' ? ($template->description ?? '') : '';
    }
}
