<?php

namespace App\Helpers;

class CurrentStoreUrl
{
    public static function get(int $storeId): string
    {
        return match ($storeId) {
            0 => env('ALOM_URL', env('APP_URL')),
            1 => env('OLCSO_URL', env('APP_URL')),
            2 => env('NAGYKER_URL', env('APP_URL')),
        };
    }
}
