<?php

namespace Alomgyar\Recommenders\Laravel\Jobs;

use Alomgyar\Customers\Customer;
use Alomgyar\Recommenders\Recommender;
use Alomgyar\Templates\Email\TemplatedMail;
use Alomgyar\Templates\Entity\TemplatedMailEntity;
use Alomgyar\Templates\Services\ContentParserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RecommenderSendJob implements ShouldQueue
{
    private Recommender $recommender;

    private Customer $customer;

    public function __construct(Customer $customer, Recommender $recommender)
    {
        $this->customer = $customer;
        $this->recommender = $recommender;
    }

    public function handle(ContentParserService $contentParserService)
    {
        $subject = $contentParserService->parseContent($this->recommender->subject, $this->createParseArray());
        $body = $contentParserService->parseContent($this->recommender->message_body, $this->createParseArray());

        $templatedMail = new TemplatedMailEntity();
        $templatedMail->setSubject($subject);
        $templatedMail->setBody($body);
        $templatedMail->setStoreId($this->recommender->store ?? 0);

        Mail::to($this->customer->email)->send(new TemplatedMail($templatedMail));
    }

    private function createParseArray()
    {
        $imageUrl = env('BACKEND_URL').'/storage/'.$this->recommender->promotedProduct->cover;

        return [
            'LAST_NAME' => $this->customer->customerFirstName,
            'ORIGINAL_BOOK' => $this->recommender->originalProduct->title,
            'PROMOTED_BOOK' => $this->recommender->promotedProduct->title,
            'PROMOTED_BOOK_DESCRIPTION' => $this->recommender->promotedProduct->description,
            // PROMOTED_BOOK_COVER' => "<img style='width: 100%' src='{$imageUrl}' alt='{$this->recommender->promotedProduct->title}'>",
            'PROMOTED_BOOK_COVER' => "<img style='display:block; margin-left:auto; margin-right:auto; width: 50%;' src='{$imageUrl}' alt='{$this->recommender->promotedProduct->title}'>",
        ];
    }
}
