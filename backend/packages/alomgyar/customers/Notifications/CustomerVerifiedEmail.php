<?php

namespace Alomgyar\Customers\Notifications;

use Alomgyar\Templates\Services\TemplateContentService;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerVerifiedEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        $content = TemplateContentService::create()->getTemplateContent('customer-verified');

        return (new MailMessage)
            ->subject($content->subject)
            ->view('templates::email.common', ['body' => $content->description]);
    }
}
