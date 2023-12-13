<?php

namespace Alomgyar\Orders\Mail;

use Alomgyar\Orders\Helpers\HTMLBuilder;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $template = TemplateContentService::create()->getTemplateContent('checkout', $this->order->store, true);

        $storePath = storage_path('app/public/barcode');

        if (! is_dir($storePath)) {
            mkdir($storePath, 0775, true);
        }

        Storage::disk('public')->put("barcode/{$this->order->order_number}.png", base64_decode(DNS1DFacade::getBarcodePNG($this->order->order_number, 'C128', 2, 65)));

        $builder = new HTMLBuilder($this->order);

        $subject = ContentParserService::parse($template->subject, ['ORDER_ID' => $this->order->order_number]);

        $body = ContentParserService::parse($template->description, [
            'ORDER_ID' => $this->order->order_number,
            'CUSTOMER_NAME' => $this->order->customer->customerFullName,
            'CUSTOMER_PHONE' => $this->order->customer->phone ?? '',
            'MY_ORDERS' => $builder->buildOrderNumber(),
            'FOLLOW_CODE' => $builder->buildFollowCode(),
            'PRODUCT_TABLE' => $builder->buildProductTable(),
            'SHIPPING_INFORMATION' => $builder->buildShippingInformaiton(),
            'BILLING_INFORMATION' => $builder->buildBillingTable(),
            'BARCODE' => $builder->buildBarCode(),
            'EBOOK' => $builder->buildEBookOnlySection(),
            'TRANSFER_INFO' => $builder->buildTransferInformation(),
            'NOTES' => $this->order->message ?? '',
        ]
        );

        return $this
            ->from($this->getFromAddress(), $this->getFromName())
            ->replyTo($this->getReplyAddress())
            ->subject($subject)
            ->view('orders::emails.order', [
                'contentBody' => $body,
                'storeId' => $this->order->store,
            ]);
    }

    private function getFromAddress(): string
    {
        return match ($this->order->store) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            default => env('MAIL_FROM_ADDRESS'),
        };
    }

    private function getFromName(): string
    {
        return match ($this->order->store) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            2 => env('NAGYKER_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            default => env('MAIL_FROM_NAME'),
        };
    }

    private function getReplyAddress(): string
    {
        return match ($this->order->store) {
            1 => env('OLCSOKONYVEK_MAIL_REPLY_ADDRESS', env('MAIL_REPLY_ADDRESS')),
            default => env('MAIL_REPLY_ADDRESS'),
        };
    }
}
