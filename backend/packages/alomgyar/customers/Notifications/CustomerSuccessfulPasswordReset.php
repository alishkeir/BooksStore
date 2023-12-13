<?php

namespace Alomgyar\Customers\Notifications;

use Alomgyar\Customers\Customer;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerSuccessfulPasswordReset extends Notification implements ShouldQueue
{
    use Queueable;

    private TemplateContentService $templateContentService;

    public function __construct()
    {
        $this->templateContentService = (new TemplateContentService());
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = Customer::whichStore($notifiable);
        $url .= '#action:feedback|code:501';

        $content = $this->templateContentService->getTemplateContent('password-reset-successful');
        $body = ContentParserService::parse($content->description, compact('url'));

        $storeId = $notifiable->store;
        $fromEmail = $storeId === 0 ? config('mail.from.address') : config('mail.olcs_from.address');

        return (new MailMessage)
            ->from($fromEmail)
            ->subject($content->subject)
            ->view('templates::email.common', compact('body', 'storeId'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
