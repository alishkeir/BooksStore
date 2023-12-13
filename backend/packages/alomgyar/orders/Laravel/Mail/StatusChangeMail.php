<?php

namespace Alomgyar\Orders\Laravel\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class StatusChangeMail extends Mailable implements ShouldQueue
{
    private string $body;

    private int $storeId;

    public function __construct(string $subject, string $body, int $storeId = 0)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->storeId = $storeId;
    }

    public function build()
    {
        $body = $this->body;
        $storeId = $this->storeId;

        return $this
            ->from($this->getFromAddress(), $this->getFromName())
            ->replyTo($this->getReplyAddress())
            ->subject($this->subject)
            ->view('templates::email.common', compact('body', 'storeId'));
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
