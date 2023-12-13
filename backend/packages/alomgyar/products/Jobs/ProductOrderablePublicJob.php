<?php

namespace Alomgyar\Products\Jobs;

use Alomgyar\Products\Product;
use Alomgyar\Templates\Email\TemplatedMail;
use Alomgyar\Templates\Entity\TemplatedMailEntity;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use App\Helpers\CurrentStoreUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ProductOrderablePublicJob implements ShouldQueue
{
    private Product $product;

    private int $store;

    private $email;

    public function __construct(Product $product, $email, $store)
    {
        $this->product = $product;
        $this->email = $email;
        $this->store = $store ?? 0;
    }

    public function handle()
    {
        $template = TemplateContentService::create()->getTemplateContent('product-orderable', $this->store, true);

        $body = ContentParserService::parse($template->description, [
            'CUSTOMER_NAME' => 'Érdeklődő',
            'PRODUCT_TITLE' => $this->product->title,
            'PRODUCT_AUTHORS' => $this->product->authors,
            'PRODUCT_DESCRIPTION' => $this->product->description,
            'PRODUCT_URL' => CurrentStoreUrl::get($this->store).'/konyv/'.$this->product->slug,
            'PRODUCT_IMAGE' => env('BACKEND_URL').'/storage/'.$this->product->cover,
        ]);

        $subject = ContentParserService::parse($template->subject, [
            'PRODUCT_TITLE' => $this->product->title,
            'PRODUCT_AUTHORS' => $this->product->authors,
        ]);

        $templatedMailEntity = new TemplatedMailEntity();
        $templatedMailEntity->setBody($body);
        $templatedMailEntity->setSubject($subject);
        $templatedMailEntity->setStoreId($this->store);

        Mail::to($this->email)->send(new TemplatedMail($templatedMailEntity));
    }
}
