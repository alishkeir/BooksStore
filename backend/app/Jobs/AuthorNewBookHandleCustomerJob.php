<?php

namespace App\Jobs;

use Alomgyar\Customers\CustomerAuthor;
use Alomgyar\Products\Product;
use Alomgyar\Products\Services\ProductService;
use Alomgyar\Templates\Email\TemplatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AuthorNewBookHandleCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $product;

    private $customerAuthor;

    private $author;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product, CustomerAuthor $customerAuthor, $author)
    {
        $this->product = $product;
        $this->customerAuthor = $customerAuthor;
        $this->author = $author;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // GET TEMPLATE
        $templatedMail = (new ProductService)->getTemplatedMail($this->product, $this->customerAuthor->customer, $this->author);

        // GET EMAIL
        $customerEmail = str_replace(' ', '', trim($this->customerAuthor?->customer?->email));

        if (! $customerEmail || empty($customerEmail)) {
            return;
        }
        Mail::to($customerEmail)->send(new TemplatedMail($templatedMail));

        (new ProductService)->storeSend($this->customerAuthor->customer->id, $this->author->id, $this->product->id);
    }
}
