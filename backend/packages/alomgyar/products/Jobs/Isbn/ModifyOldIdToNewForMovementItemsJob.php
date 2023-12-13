<?php

namespace Alomgyar\Products\Jobs\Isbn;

use Alomgyar\Product_movements\ProductMovementItems;
use Alomgyar\Products\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ModifyOldIdToNewForMovementItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $productMovementItem;

    public $newId;

    public $oldId;

    public function __construct(ProductMovementItems $productMovementItem, $newId, $oldId)
    {
        $this->productMovementItem = $productMovementItem;
        $this->newId = $newId;
        $this->oldId = $oldId;
    }

    public function handle()
    {
        if (! $this->modificationExists()) {
            // CHANGE THE PRODUCT ID ON THE MOVEMENT
            $modifiedProductMovementItem = DB::table('product_movements_items')->where('id', $this->productMovementItem->id)->update([
                'product_id' => $this->newId,
            ]);

            // INACTIVATE OLD BOOK
            $product = Product::where('id', $this->oldId)->update([
                'status' => Product::STATUS_INACTIVE,
            ]);

            // SAVE THE CHANGES TO A SEPARATE TABLE
            $changedData = DB::table('product_movements_items_isbn_change')->insert([
                'product_movement_items_id' => $this->productMovementItem->id,
                'old_product_id' => $this->oldId,
                'new_product_id' => $this->newId,
            ]);
        }
    }

    public function modificationExists()
    {
        $exists = DB::table('product_movements_items_isbn_change')
            ->where('product_movement_items_id', $this->productMovementItem->id)
            ->where('old_product_id', $this->oldId)
            ->where('new_product_id', $this->newId)
            ->exists();

        return $exists;
    }
}
