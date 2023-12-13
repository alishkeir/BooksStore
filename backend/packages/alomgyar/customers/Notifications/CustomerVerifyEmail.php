<?php

namespace Alomgyar\Customers\Notifications;

use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class CustomerVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    private int $storeId;

    public function __construct(int $storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        $url = str_replace('http://', 'https://', $url);

        Log::info('URL: '.$url);

        $contentTemplate = TemplateContentService::create()->getTemplateContent('customer-verify');

        $contentParser = (new ContentParserService());

        $subject = $contentParser->parseContent($contentTemplate->subject, []);
        $body = $contentParser->parseContent($contentTemplate->description, ['URL' => $url]);

        return (new MailMessage)
            ->from($this->getFromAddress(), $this->getFromName())
            ->replyTo($this->getReplyAddress())
            ->subject($subject)
            ->view('templates::email.common', [
                'storeId' => $this->storeId,
                'body' => $body,
            ]);
    }

    private function getFromAddress(): string
    {
        return match ($this->storeId) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            default => env('MAIL_FROM_ADDRESS'),
        };
    }

    private function getFromName(): string
    {
        return match ($this->storeId) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            2 => env('NAGYKER_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            default => env('MAIL_FROM_NAME'),
        };
    }

    private function getReplyAddress(): string
    {
        return match ($this->storeId) {
            1 => env('OLCSOKONYVEK_MAIL_REPLY_ADDRESS', env('MAIL_REPLY_ADDRESS')),
            default => env('MAIL_REPLY_ADDRESS'),
        };
    }
}
