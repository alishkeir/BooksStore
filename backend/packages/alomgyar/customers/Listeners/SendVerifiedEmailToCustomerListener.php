<?php

namespace Alomgyar\Customers\Listeners;

use Alomgyar\Customers\Events\CustomerVerifiedEvent;
use Alomgyar\Templates\Email\TemplatedMail;
use Alomgyar\Templates\Entity\TemplatedMailEntity;
use Alomgyar\Templates\Services\TemplateContentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVerifiedEmailToCustomerListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     *
     * @return void
     */
    public function handle(CustomerVerifiedEvent $event)
    {
        $templateContentService = TemplateContentService::create();

        $content = $templateContentService->getTemplateContent('customer-verified', $event->customer->store);

        $templatedMail = new TemplatedMailEntity();

        $templatedMail->setSubject($content->subject);
        $templatedMail->setBody($content->description);
        $templatedMail->setStoreId($event->customer->store);

        Mail::to($event->customer->email)->send(new TemplatedMail($templatedMail));
    }
}
