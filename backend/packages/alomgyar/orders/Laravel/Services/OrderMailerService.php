<?php

namespace Alomgyar\Orders\Laravel\Services;

use Alomgyar\Orders\Helpers\HTMLBuilder;
use Alomgyar\Orders\Laravel\Mail\StatusChangeMail;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use App\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Emails\InternalEmail;

class OrderMailerService
{
    public static function create(): self
    {
        return new self();
    }

    public function sendStatusEmail(Order $order, $slug): void
    {
        switch ($order->status) {
            case Order::STATUS_NEW:
                // @todo akármilyen extra adat státusz specifikusan
                break;
        }

        try {
            $template = TemplateContentService::create()->getTemplateContent($slug, $order->store, true);
        } catch (ModelNotFoundException $e) {
            return;
        }

        $templateParser = new ContentParserService();

        $builder = new HTMLBuilder($order);

        $subject = $templateParser->parseContent($template->subject, [
            'ORDER_ID' => $order->order_number,
            'CUSTOMER_NAME' => $order->customer->customerFullName ?? 'Vásárló', //TODO
            'CUSTOMER_PHONE' => $order->customer->phone ?? '',
        ]);

        $body = $templateParser->parseContent($template->description, [
            'ORDER_ID' => $order->order_number,
            'CUSTOMER_NAME' => $order->customer->customerFullName ?? 'Vásárló', //TODO
            'MY_ORDERS' => $builder->buildOrderNumber(),
            'FOLLOW_CODE' => $builder->buildFollowCode(),
            'PRODUCT_TABLE' => $builder->buildProductTable(),
            'SHIPPING_INFORMATION' => $builder->buildShippingInformaiton(),
            'BILLING_INFORMATION' => $builder->buildBillingTable(),
            'BARCODE' => $builder->buildBarCode(),
            'EBOOK' => $builder->buildEBookOnlySection(),
            'TRANSFER_INFO' => $builder->buildTransferInformation(),
            'NOTES' => $order->message ?? '',
        ]);

        if ($order->customer ?? false) {
            dispatch(function () use ($subject, $body, $order) {
                Mail::to(trim($order->customer->email))->send(new StatusChangeMail($subject, $body, $order->store));
            })->afterResponse();
        }
    }

    public function sendInvoiceGenerationError(): void
    {
        $template = TemplateContentService::create()->getTemplateContent('invoice_generation_error');

        if (! $template) {
            return;
        }
        // MODIFY MAIL ADDRESS, SO NOT SKVAD IS NOTIFIED
        Mail::to(option('contact_email', 'zoltan.papp@weborigo.eu'))
            ->send(new InternalEmail($template->subject, $template->description));
    }

    public function sendInvoiceGenerationFail($message): void
    {
        $template = TemplateContentService::create()->getTemplateContent('invoice_generation_fail');

        if (! $template) {
            return;
        }
        // MODIFY MAIL ADDRESS, SO NOT SKVAD IS NOTIFIED
        Mail::to(option('contact_email', 'zoltan.papp@weborigo.eu'))
            ->send(new InternalEmail($template->subject, $template->description.' - '.$message));
    }
}
