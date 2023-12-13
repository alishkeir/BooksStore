<?php

namespace Alomgyar\Templates\Email;

use Alomgyar\Templates\Entity\TemplatedMailEntity as TemplatedMailEntity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class TemplatedMail extends Mailable implements ShouldQueue
{
    private TemplatedMailEntity $templatedMail;

    public function __construct(TemplatedMailEntity $templatedMail)
    {
        $this->templatedMail = $templatedMail;
    }

    public function build()
    {
        return $this
            ->from($this->getFromAddress(), $this->getFromName())
            ->replyTo($this->getReplyAddress())
            ->subject($this->templatedMail->getSubject())
            ->view('templates::email.common', [
                'storeId' => $this->templatedMail->getStoreId(),
                'body' => $this->templatedMail->getBody(),
                'footerContent' => $this->templatedMail->getFooterContent(),
            ]);
    }

    private function getFromAddress(): string
    {
        return match ($this->templatedMail->getStoreId()) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            default => env('MAIL_FROM_ADDRESS'),
        };
    }

    private function getFromName(): string
    {
        return match ($this->templatedMail->getStoreId()) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            2 => env('NAGYKER_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            default => env('MAIL_FROM_NAME'),
        };
    }

    private function getReplyAddress(): string
    {
        return match ($this->templatedMail->getStoreId()) {
            1 => env('OLCSOKONYVEK_MAIL_REPLY_ADDRESS', env('MAIL_REPLY_ADDRESS')),
            default => env('MAIL_REPLY_ADDRESS'),
        };
    }
}
