<?php
/*
Author: Hódi
Date: 2021. 06. 28. 13:39
Project: alomgyar-webshop-be
*/

namespace App\Helpers;

use Alomgyar\Methods\ShippingMethod;
use Illuminate\Support\Facades\Cache;

class StoreHelper
{
    public const ALOMGYAR = 0;

    public const OLCSOKONYVEK = 1;

    public const NAGYKER = 2;

    public static function currentStore(): string
    {
        $stores = config('pam.store_urls');

        return $stores[request('store')];
    }

    public static function currentStoreName(): string
    {
        switch (request('store')) {
            case 1:
                return 'Olcsókönyvek';
            case 2:
                return 'Nagyker';
            default:
                return 'Álomgyár';
        }
    }

    public static function freeShippingLimit($store_id = 1): int
    {
        $store = (int) request('store') ?? $store_id;

        return match ($store) {
            1 => option('free_shipping_limit_olcsokonyvek'),
            2 => option('free_shipping_limit_nagyker'),
            default => option('free_shipping_limit_alomgyar') ?? 10000,
        };
    }

    public static function showFreeShippingBanner($store_id = 1): bool
    {
        $store = (int) request('store') ?? $store_id;

        return match ($store) {
            1 => option('free_shipping_banner_show_olcsokonyvek'),
            2 => option('free_shipping_banner_show_nagyker'),
            default => option('free_shipping_banner_show_alomgyar') ?? false,
        };
    }
}
