<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Cache;

class AffiliateHelper
{
    const AFFILIATE_RELEASE_DATE = '2023-09-01';
    public static function flushAffiliateCacheForCustomer($customerId)
    {
        if (!$customerId) return;
        Cache::forget('customer_total_redeems_'. $customerId);
        Cache::forget('customer_balance_'. $customerId);
        Cache::forget('affiliate_redeem_count_'. $customerId);
        self::flushAffiliateTotalCache();
    }
    public static function flushAffiliateTotalCache()
    {
        Cache::forget('affiliate_unpaid_credit_total');
        Cache::forget('affiliate_paid_credit_total');
    }
}
