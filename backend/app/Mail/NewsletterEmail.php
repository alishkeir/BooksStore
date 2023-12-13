<?php

namespace App\Mail;

use Alomgyar\Templates\Services\ContentParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterEmail extends Mailable
{
    use Queueable, SerializesModels;

    private int $storeId;
    private $url;
    private $_subject;
    private $description;

    public function __construct(int $storeId,$url,$subject,$description)
    {
        $this->storeId = $storeId;
        $this->url = $url;
        $this->_subject = $subject;
        $this->description = $description;
    }


    public function build()
    {
        $url = str_replace('http://', 'https://', $this->url);

        $contentParser = (new ContentParserService());

        $body = $contentParser->parseContent($this->description, ['URL' => $url]);

        return $this
            ->from($this->getFromAddress(), $this->getFromName())
            ->replyTo($this->getReplyAddress())
            ->subject($this->_subject)
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
