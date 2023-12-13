<?php

namespace Alomgyar\Templates\Entity;

class TemplatedMailEntity
{
    private string $subject;

    private string $body;

    private string|null $footerContent = null;

    private int $storeId;

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getFooterContent(): ?string
    {
        return $this->footerContent;
    }

    public function setFooterContent(?string $footerContent): void
    {
        $this->footerContent = $footerContent;
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function setStoreId(int $storeId): void
    {
        $this->storeId = $storeId;
    }
}
