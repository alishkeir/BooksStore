<?php

namespace Alomgyar\Products\Mails;

use Alomgyar\Products\Product;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductsHasLowStockMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private array $items;

    private $sender;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $items, $sender)
    {
        $this->items = $items;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = TemplateContentService::create()->getTemplateContent('low-stock', 0, true);

        $productsListHtml = view('products::partials.low-stock-items', ['products' => $this->items])->render();

        $body = ContentParserService::parse($template->description, [
            'STOCK_LIMIT' => Product::STOCK_LIMIT,
            'PRODUCT_LIST' => $productsListHtml,
        ]);
        $subject = ContentParserService::parse($template->subject, []);

        return $this
            ->from($this->sender, 'Álomgyár könyvesboltok')
            ->subject($subject)
            ->view('products::emails.product', [
                'contentBody' => $body,
                'storeId' => 0,
            ]);
    }
}
