<?php

namespace App\Mail;

use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use App\Helpers\StoreHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $prospect;

    private int $store;

    public function __construct($prospect, int $store)
    {
        $this->prospect = $prospect;
        $this->store = $store;
    }

    public function build(): self
    {
        $template = TemplateContentService::create()->getTemplateContent('contact', $this->store, true);
        $body = ContentParserService::parse($template->description, $this->prospect);

        $storeName = StoreHelper::currentStoreName();

        return $this
            ->from($this->getFromAddress(), $this->getFromName())
            ->replyTo($this->getReplyAddress())
            ->subject(str_replace('%STORE_NAME%', $storeName, $template->subject))
            ->view('emails.common', [
                'body' => $body,
                'storeId' => $this->store,
            ]);
    }

    private function getFromAddress(): string
    {
        return match ($this->store) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            default => env('MAIL_FROM_ADDRESS'),
        };
    }

    private function getFromName(): string
    {
        return match ($this->store) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            2 => env('NAGYKER_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            default => env('MAIL_FROM_NAME'),
        };
    }

    private function getReplyAddress(): string
    {
        return match ($this->store) {
            1 => env('OLCSOKONYVEK_MAIL_REPLY_ADDRESS', env('MAIL_REPLY_ADDRESS')),
            default => env('MAIL_REPLY_ADDRESS'),
        };
    }
}
