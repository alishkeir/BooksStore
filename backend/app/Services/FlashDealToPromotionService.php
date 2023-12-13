<?php

namespace App\Services;

use Alomgyar\Products\Jobs\FlashSalePromotionAddProductToPromotionJob;
use Alomgyar\Products\Jobs\FlashSalePromotionModifyProductDiscountPriceJob;
use Alomgyar\Products\Product;
use Alomgyar\Products\ProductPrice;
use Alomgyar\Promotions\Promotion;
use Alomgyar\Promotions\Scopes\NotShowFlashDealScope;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FlashDealToPromotionService
{
    const FLASH_DEAL_NAME = 'VillámAkció';

    // SAVE FLASH DEAL PRODUCTS ITEMS

    // 1. CREATE PROMOTION
    // 2. QUERY FOR THE INTERESTING PRODUCTS (& PRICES)
    // 3. RUN AN UPDATE JOB
    // 3. a. UPDATE CURRENT (SALE) PRICES
    // 3. b. ADD THESE PRODUCTS FOR THE PROMOTION WITH THE DESIRED PRICES

    public function create($sourceDiscountPercentage, $targetDiscountPercentage, $storeNumber, $fromDate, $toDate, $createdById)
    {
        // ORIGINAL QUERY
        // $sql = "UPDATE `product_price` pp
        // LEFT JOIN product as p ON p.id = pp.product_id
        // SET pp.discount_percent = ".$this->target.",
        // pp.price_sale = ROUND(pp.price_list / 100 * (100 - ".$this->target.")),
        // pp.price_sale_original = ROUND(pp.price_list / 100 * (100 - ".$this->target."))
        // WHERE pp.store=".$this->stores[$this->store]." AND p.status = 1 AND p.type = 0 AND p.state = 0 AND p.is_new != 1
        // AND pp.discount_percent = ".$this->source;
        // MAGIC

        $instant = false;
        // CHECK IF FLASH SALE STARTS WITHIN 5 MINUTES
        if (Carbon::parse($fromDate)->format('Y-m-d H:i:s') <= Carbon::now()->addMinutes(5)) {
            $instant = true;
        }

        $newlyCreatedPromotion = $this->generateNewFlashDealPromotion($storeNumber, $fromDate, $toDate, $createdById);

        if ($newlyCreatedPromotion) {
            $productPrices = ProductPrice::query()
                ->select('product_price.*')
                ->where('store', $storeNumber)
                ->where('discount_percent', $sourceDiscountPercentage)
                ->with('product')
                ->whereHas('product', function ($query) {
                    $query->where('type', Product::BOOK)
                    ->where('status', Product::STATUS_ACTIVE)
                    ->where('state', Product::STATE_NORMAL)
                    ->where('is_new', Product::NOT_NEW);
                })
                ->get();

            foreach ($productPrices as $key => $productPrice) {
                $salePrice = round($productPrice->price_list / 100 * (100 - $targetDiscountPercentage), 0);
                FlashSalePromotionAddProductToPromotionJob::dispatch($storeNumber, $productPrice->product_id, $newlyCreatedPromotion->id, $salePrice);
                if ($instant) {
                    FlashSalePromotionModifyProductDiscountPriceJob::dispatch($productPrice, $targetDiscountPercentage, $salePrice);
                }
            }
        }
    }

    public function updateEndDate(Promotion $promotion, $newToDate)
    {
        $title = $this->generateFlashDealName($promotion->active_from, $newToDate);
        $promotion->active_to = $newToDate;
        $promotion->title = $title;
        $promotion->slug = Str::slug($title);
        $promotion->save();

        return $promotion;
    }

    public function generateFlashDealName($fromDate, $toDate)
    {
        $title = self::FLASH_DEAL_NAME.' '.Carbon::parse($fromDate)->format('Y-m-d H:i:s').' - '.Carbon::parse($toDate)->format('Y-m-d H:i:s');

        return $title;
    }

    public function generateNewFlashDealPromotion($storeNumber, $fromDate, $toDate, $createdById)
    {
        // CREATE PROMOTION
        // ADD THESE PRODUCTS TO PROMOTION
        $newlyCreatedPromotion = null;

        $title = $this->generateFlashDealName($fromDate, $toDate);
        $slug = Str::slug($title);
        $promotionExists = Promotion::withoutGlobalScopes()->where('slug', $slug)->exists();

        if (! $promotionExists) {
            $newlyCreatedPromotion = Promotion::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'status' => Promotion::STATUS_ACTIVE,
                'is_flash_deal' => Promotion::IS_FLASH_DEAL,
                'store_0' => $storeNumber == 0 ? Promotion::STATUS_ACTIVE : Promotion::STATUS_INACTIVE,
                'store_1' => $storeNumber == 1 ? Promotion::STATUS_ACTIVE : Promotion::STATUS_INACTIVE,
                'store_2' => $storeNumber == 2 ? Promotion::STATUS_ACTIVE : Promotion::STATUS_INACTIVE,
                'active_from' => $fromDate,
                'active_to' => $toDate,
                'created_by_id' => $createdById,
            ]);
        }
        //$this->deactivatePreviousFlashDealPromotions($newlyCreatedPromotion->id);

        return $newlyCreatedPromotion;
    }

    // ALSO, SET PREVIOUS FLASH PROMOTION INACTIVE
    // status = Promotion::STATUS_INACTIVE
    // THIS MEANS, ONLY 1 FLASH PROMOTION CAN BE ACTIVE AT 1 TIME
    // ALSO WE CAN PREVENT MISCLICK SITUATIONS
    public function deactivatePreviousFlashDealPromotions($excludeFlashDealPromotionId)
    {
        $previousFlashDealPromotions = Promotion::query()
            ->withoutGlobalScope(NotShowFlashDealScope::class)
            ->flashDeals()
            ->active()
            ->where('id', '!=', $excludeFlashDealPromotionId)
            ->each(function ($previousFlashDealPromotion) {
                $previousFlashDealPromotion->update([
                    'status' => Promotion::STATUS_INACTIVE,
                ]);
            });

        return $previousFlashDealPromotions;
    }
}
