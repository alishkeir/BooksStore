<?php

namespace Alomgyar\Products\Services;

use Alomgyar\Authors\Author;
use Alomgyar\Customers\Customer;
use Alomgyar\Customers\CustomerAuthor;
use Alomgyar\Customers\Models\CustomerAuthorMail;
use Alomgyar\Products\Product;
use Alomgyar\Templates\Entity\TemplatedMailEntity;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use App\Helpers\CurrentStoreUrl;
use App\Jobs\AuthorNewBookHandleCustomerJob;
use Exception;

class ProductService
{
    public static function create()
    {
        return new self();
    }

    public function sendWriterHasNewBook(Product $product)
    {
        // BOOK -> count(authors) = 1
        foreach ($product->author as $author) {
            $customerAuthors = CustomerAuthor::query()
                ->with('customer')
                ->where('author_id', $author->id)
                ->get();

            if (! $customerAuthors) {
                continue;
            }
            foreach ($customerAuthors as $customerAuthor) {
                // CHECKS IF THE CUSTOMER ALREADY RECIEVED THE NOTIFICATION
                if (! $customerAuthor->customer->id || (new ProductService)->alreadySent($customerAuthor->customer->id, $author->id, $product->id)) {
                    continue;
                }
                // JOB DISPATCH HERE
                // TO OPTIMIZE THE HEAVY DUTY
                // HANDLE NOTIFICATION
                AuthorNewBookHandleCustomerJob::dispatch($product, $customerAuthor, $author);
            }
        }
    }

    public function getTemplatedMail(Product $product, Customer $customer, Author $author): TemplatedMailEntity
    {
        $storeId = $customer->store;

        $templatedMailEntity = new TemplatedMailEntity();

        $template = TemplateContentService::create()->getTemplateContent('author_new_book', $storeId, true);
        $contentParser = new ContentParserService();

        $subject = $contentParser->parseContent($template->subject, [
            'AUTHOR_NAME' => $author->title,
        ]);

        $body = $contentParser->parseContent($template->description, [
            'AUTHOR_NAME' => $author->title,
            'CUSTOMER_NAME' => $customer->firstname,
            'PRODUCT_TITLE' => $product->title,
            'PRODUCT_URL' => CurrentStoreUrl::get($customer->store).'/konyv/'.$product->slug,
            'UNSUBESCRIBE_URL' => '#',
        ]);

        $templatedMailEntity->setStoreId($storeId);
        $templatedMailEntity->setSubject($subject);
        $templatedMailEntity->setBody($body);

        return $templatedMailEntity;
    }

    public function alreadySent($customer_id, $authorId, $productId): bool
    {
        return (bool) CustomerAuthorMail::where([
            'customer_id' => $customer_id,
            'author_id' => $authorId,
            'product_id' => $productId,
        ])->exists();
    }

    public function storeSend($customer_id, $authorId, $productId): void
    {
        try {
            CustomerAuthorMail::create([
                'customer_id' => $customer_id,
                'author_id' => $authorId,
                'product_id' => $productId,
            ]);
        } catch (Exception $e) {
            info(serialize($e->getMessage()));
        }
    }
}
