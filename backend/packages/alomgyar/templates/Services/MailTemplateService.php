<?php

namespace Alomgyar\Templates\Services;

class MailTemplateService
{
    public static function getByStoreId(int $storeId): string
    {
        return match ($storeId) {
            1 => 'layouts.email.olcsokonyvek',
            2 => 'layouts.email.nagyker',
            default => 'layouts.email.alomgyar',
        };
    }
}
