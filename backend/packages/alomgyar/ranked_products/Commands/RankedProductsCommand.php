<?php

namespace Alomgyar\RankedProducts\Commands;

use Alomgyar\RankedProducts\Model\RankedProduct;
use Alomgyar\RankedProducts\Repository\RankedProductRepository;
use Illuminate\Console\Command;

class RankedProductsCommand extends Command
{
    protected $signature = 'ranked:determine';

    protected $description = 'Determine related products.';

    public function handle()
    {
        RankedProduct::truncate();

        // pre
        $this->determinePreOrders(0);
        $this->determinePreOrders(1);
        $this->determinePreOrders(2);
        // sold
        $this->determineBestSellers(0);
        $this->determineBestSellers(1);
        $this->determineBestSellers(2);
        // esold
        $this->determineEBookBestSellers(0);
        $this->determineEBookBestSellers(1);
        $this->determineEBookBestSellers(2);
        // discount_sold
        $this->determineDiscountSold(0);
        $this->determineDiscountSold(1);
        $this->determineDiscountSold(2);

        $this->info('Seeding ranked products done');
    }

    private function determinePreOrders(int $storeId): void
    {
        $repository = (new RankedProductRepository);

        $preOrders = $repository->getBestPreorders($storeId);

        foreach ($preOrders as $key => $item) {
            RankedProduct::create([
                'product_id' => $item->id,
                'rank' => $key + 1,
                'type' => 'pre',
                'store_id' => $storeId,
            ]);
        }
    }

    private function determineBestSellers(int $storeId)
    {
        $repository = (new RankedProductRepository);

        $bestSellers = $repository->getBestSellers($storeId);

        foreach ($bestSellers as $key => $item) {
            RankedProduct::create([
                'product_id' => $item->id,
                'rank' => $key + 1,
                'type' => 'sold',
                'store_id' => $storeId,
            ]);
        }
    }

    private function determineEBookBestSellers(int $storeId)
    {
        $repository = (new RankedProductRepository);

        $ebooks = $repository->getEbookBestSellers($storeId);

        foreach ($ebooks as $key => $item) {
            RankedProduct::create([
                'product_id' => $item->id,
                'rank' => $key + 1,
                'type' => 'e_sold',
                'store_id' => $storeId,
            ]);
        }
    }

    private function determineDiscountSold(int $storeId)
    {
        $repository = (new RankedProductRepository);

        $bestDiscounteds = $repository->getBestDiscounted($storeId);

        foreach ($bestDiscounteds as $key => $item) {
            RankedProduct::create([
                'product_id' => $item->id,
                'rank' => $key + 1,
                'type' => 'discount_sold',
                'store_id' => $storeId,
            ]);
        }
    }
}
