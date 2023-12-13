<?php

namespace Alomgyar\Products\Mails;

use Alomgyar\Customers\Customer;
use Alomgyar\Products\Product;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use App\Helpers\CurrentStoreUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class ProductHasNormalStateMail extends Mailable implements ShouldQueue
{
    private Product $product;

    private int $store;

    private Customer $customer;

    public function __construct(Product $product, Customer $customer)
    {
        $this->product = $product;
        $this->customer = $customer;
        $this->store = $customer->store ?? 0;
    }

    public function build()
    {
        $template = TemplateContentService::create()->getTemplateContent('product-has-normal-status', $this->store, true);

        $body = ContentParserService::parse($template->subject, [
            'CUSTOMER_NAME' => $this->customer->customerFullName,
            'PRODUCT_TITLE' => $this->product->title,
            'PRODUCT_AUTHORS' => $this->product->authors,
            'PRODUCT_DESCRIPTION' => $this->product->description,
            'PRODUCT_IMAGE' => env('BACKEND_URL').'/storage/'.$this->product->cover,
            'PRODUCT_URL' => CurrentStoreUrl::get($this->customer->store).'/konyv/'.$this->product->slug,
        ]);

        $subject = ContentParserService::parse($template->subject, [
            'PRODUCT_TITLE' => $this->product->title,
            'PRODUCT_AUTHORS' => $this->product->authors,
        ]);

        return $this
            ->from($this->getFromAddress(), $this->getFromName())
            ->replyTo($this->getReplyAddress())
            ->subject($subject)
            ->view('orders::emails.order', [
                'contentBody' => $body,
                'storeId' => $this->store,
            ]);
    }

    private function getFromAddress(): string
    {
        return match ($this->store) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            default => env('MAIL_FROM_ADDRESS'),
        };
    }

    private function getFromName(): string
    {
        return match ($this->store) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            2 => env('NAGYKER_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            default => env('MAIL_FROM_NAME'),
        };
    }

    private function getReplyAddress(): string
    {
        return match ($this->store) {
            1 => env('OLCSOKONYVEK_MAIL_REPLY_ADDRESS', env('MAIL_REPLY_ADDRESS')),
            default => env('MAIL_REPLY_ADDRESS'),
        };
    }
}
