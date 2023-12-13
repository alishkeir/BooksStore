<?php

namespace App\Jobs;

use Alomgyar\Product_movements\ProductMovement;
use Alomgyar\Warehouses\Inventory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateStockQuantityForInventoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $productId;

    public $quantity;

    public $warehouseId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productId, $quantity, $warehouseId)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->warehouseId = $warehouseId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelStockIn = null;
        $modelStockOut = null;

        $getQuantity = Inventory::query()
        ->where('product_id', $this->productId)
        ->where('warehouse_id', $this->warehouseId)
        ->first();

        if (! $getQuantity) {
            $createQuantity = Inventory::create([
                'warehouse_id' => $this->warehouseId,
                'product_id' => $this->productId,
                'stock' => $this->quantity,
            ]);
            Log::channel('warehouse')->info('Stock created with Product ID: '.$this->productId);

            return;
        }

        $stockReference = ProductMovement::generateReferenceNr();

        if ($this->quantity > $getQuantity->stock) {
            // Ha több van → Bevételezés, Forrás: Egyéb, Cél: Aktuális Raktár
            $modelStockIn = ProductMovement::firstOrCreate(
                ['reference_nr' => $stockReference],
                [
                    'causer_type' => 'App\User',
                    'causer_id' => 4,
                    'source_type' => 'other',
                    'source_id' => null,
                    'destination_type' => 4,
                    'destination_id' => $this->warehouseId,
                    'created_at' => time(),
                    'updated_at' => time(),
                ]
            );

            $result['stock_in'][] = [
                'product_id' => $this->productId,
                'product_movements_id' => $modelStockIn->id,
                'stock_in' => (int) $this->quantity - (int) $getQuantity->stock,
                'status' => 1,
                'stock_out' => 0,
            ];
        } elseif ($this->quantity < $getQuantity->stock) {
            // Ha kevesebb → Kivételezés, Forrás: Aktuális raktár, Cél: Egyéb

            $modelStockOut = ProductMovement::firstOrCreate(
                ['reference_nr' => $stockReference],
                [
                    'causer_type' => 'App\User',
                    'causer_id' => 4,
                    'source_type' => 'warehouse',
                    'source_id' => $this->warehouseId,
                    'destination_type' => 4,
                    'destination_id' => null,
                    'created_at' => time(),
                    'updated_at' => time(),
                ]
            );

            $result['stock_out'][] = [
                'product_id' => $this->productId,
                'product_movements_id' => $modelStockOut->id,
                'stock_in' => 0,
                'status' => 1,
                'stock_out' => (int) $getQuantity->stock - (int) $this->quantity,
            ];
        } else {
            $result['no_change'][] = [
                'product_id' => $this->productId,
                'product_movements_id' => null,
                'stock_in' => 0,
                'status' => 0,
                'stock_out' => 0,
            ];
        }
        $getQuantity->stock = $this->quantity;
        $getQuantity->save();

        if ($modelStockIn) {
            ProductMovement::addItems($modelStockIn, $result['stock_in']);
        }

        if ($modelStockOut) {
            ProductMovement::addItems($modelStockOut, $result['stock_out']);
        }

        Log::channel('warehouse')->info('Stock updated with Product ID: '.$this->productId);
    }
}
