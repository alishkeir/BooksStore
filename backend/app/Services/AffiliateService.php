<?php

namespace App\Services;

use Alomgyar\Affiliates\AffiliateRedeem;
use Alomgyar\Customers\Customer;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AffiliateService
{
    public function getCustomerBalance(Customer $customer)
    {
        if (!$customer->affiliate)
            return 0;
        return Cache::remember('customer_balance_' . $customer->id, config('cache.admin_default_cache_time'), function () use ($customer) {
            $orders = Order::where('affiliate_code', $customer->affiliate->code)->validForAffiliate();
            $this->setCalculated($orders->get());
            $ordersSum = $orders->select(DB::raw('sum(round(total_amount / '.config('pamadmin.vat-multiplier').' * affiliate_commission_percentage / 100, 0)) as total'))->get()->sum('total'); //round to 0 decimals

            $redeemsSum = $this->getCustomerTotalRedeems($customer);
            return $ordersSum - $redeemsSum;
        }); //re-calculate every 24 hours
    }
    public function getCustomerTotalRedeems(Customer $customer)
    {
        if (!$customer->affiliate)
            return 0;
        return Cache::rememberForever('customer_total_redeems_' . $customer->id, function () use ($customer) {
            $redeemsSum = 0;
            if (count($customer->affiliateRedeems) > 0) {
                $redeemsSum = $customer->affiliateRedeems->sum('amount');
            }
            return $redeemsSum;
        });
    }
    public function getUnpaidCreditTotal()
    {
        return Cache::remember('affiliate_unpaid_credit_total', config('cache.admin_default_cache_time'), function () {
            $orders = Order::whereNotNull('affiliate_code')->validForAffiliate();
            $this->setCalculated($orders->get());
            $ordersSum = $orders->select(DB::raw('sum(round(total_amount / '.config('pamadmin.vat-multiplier').' * affiliate_commission_percentage / 100, 0)) as total'))->get()->sum('total'); //round to 0 decimals

            $redeemsSum = $this->getPaidCreditTotal();

            return $ordersSum - $redeemsSum;
        }); //re-calculate every 24 hours
    }
    public function getPaidCreditTotal()
    {
        return Cache::rememberForever('affiliate_paid_credit_total', function () {
            $redeemsSum = AffiliateRedeem::sum('amount');

            return $redeemsSum ?? 0;
        });
    }
    public function getRedeemNumberForAffiliateCustomer($customerId)
    {
        return Cache::rememberForever('affiliate_redeem_count_'. $customerId, function() use ($customerId){
            return AffiliateRedeem::where('customer_id', $customerId)->count() ?? 0;
        });
    }
    public function setCalculated($orders)
    {
        foreach ($orders as $order){
            if (!$order->affiliate_calculated){
                $order->affiliate_calculated = Order::AFFILIATE_CALCULATED;
                $order->saveQuietly();
            }
        }
    }
}
