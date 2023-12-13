<?php
namespace App\Helpers;

use Alomgyar\Warehouses\Inventory;
use App\Models\Consumption;
use App\Models\SupplierInventory;
use Illuminate\Support\Facades\DB;

class InventoryHelper
{
    public static function hasOwnStock($productId,$quantity=1) {
        $totalStock = Inventory::select([
            DB::raw("SUM(stock) as total")
        ])
            ->where('product_id',$productId)
            ->first()->pluck('total');

        $supplierStock = Consumption::select([
            DB::raw("SUM(remaining_quantity) as total")
        ])
            ->where('product_id',$productId)
            ->whereNotNull('remaining_quantity')
            ->first()->pluck('total');

        return $supplierStock <= $totalStock - $quantity;
    }
    public static function getCheapestSupplier($productId,$quantity=1) {
        $cheapestFixPriceSupply = Consumption::select([
            'supplier_id',
            'price'
        ])
            ->where('product_id',$productId)
            ->where('remaining_quantity','>=',$quantity)
            ->whereNotNull('price')
            ->orderBy('price','ASC')
            ->first();

        $cheapestPercentagePriceSupply = Consumption::select([
            DB::raw("product_price.price_list * suppliers.percent/100 as price"),
            'supplier_id'
        ])
            ->where('product_id',$productId)
            ->join('suppliers','suppliers.id','consumptions.supplier_id')
            ->join('product_price','product_price.product_id','consumptions.product_id')
            ->where('remaining_quantity','>=',$quantity)
            ->whereNull('price')
            ->orderBy('price','ASC')
            ->first();


        return $cheapestPercentagePriceSupply['price'] < $cheapestFixPriceSupply['price']
            ? $cheapestPercentagePriceSupply['supplier_id']
            : $cheapestFixPriceSupply['supplier_id'];
    }

    /**
     * @throws \Exception
     */
    public static function productSourcing($productId, $quantity, $supplier, $price=null) : bool {
        if($quantity < 0){
            throw new \Exception("The quantity cannot be lower than zero.");
        }
        $item = Consumption::create([
            'supplier_id'=>$supplier,
            'product_id'=>$productId,
            'quantity'=>$quantity,
            'remaining_quantity'=>$quantity,
            'price'=>$price
        ]);

        return $item->id > 0;
    }

    /**
     * @throws \Exception
     */
    public static function productOutgoing($productId, $quantity, $supplier) : bool{
        if($quantity < 0){
            throw new \Exception("The quantity cannot be lower than zero.");
        }
        Consumption::where('supplier_id',$supplier)
            ->where('product_id',$productId)
            ->where('remaining_quantity','>=',$quantity)
            ->decrement('remaining_quantity',$quantity);

        $item = Consumption::create([
            'supplier_id'=>$supplier,
            'product_id'=>$productId,
            'quantity'=>$quantity * -1
        ]);

        return $item->id > 0;
    }

    /**
     * @throws \Exception
     */
    public static function incrementStock($productId, $warehouseId, $quantity, $supplierId=null): bool
    {
        if($quantity < 0){
            throw new \Exception("The quantity cannot be lower than zero.");
        }
        self::changeWarehouseStock($productId,$quantity,$warehouseId);

        if(!empty($supplierId)){
            self::changeSupplierStock($productId,$quantity,$supplierId);
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public static function decrementStock($productId, $warehouseId, $quantity, $supplierId=null): bool
    {
        if($quantity < 0){
            throw new \Exception("The quantity cannot be lower than zero.");
        }
        self::changeWarehouseStock($productId,$quantity * -1,$warehouseId);

        if(!empty($supplierId)){
            self::changeSupplierStock($productId,$quantity * -1,$supplierId);
        }

        return true;
    }

    private static function changeWarehouseStock($productId,$quantity,$warehouseId)
    {
        $item = Inventory::firstOrNew(
            [
                'product_id' => $productId,
                'warehouse_id' => $warehouseId
            ],
        );

        // new inventory row
        if(empty($item->id)) {
            $item->stock = $quantity;
            $item->save();
        }else{
            DB::table('inventories')
                ->where('id', $item->id)
                ->increment('stock', $quantity);
        }
    }
    private static function changeSupplierStock($productId,$quantity,$supplierId)
    {
        $item = SupplierInventory::firstOrNew(
            [
                'product_id' => $productId,
                'supplier_id' => $supplierId
            ],
        );

        // new inventory row
        if(empty($item->id)) {
            $item->stock = $quantity;
            $item->save();
        }else{
            DB::table('supplier_inventories')
                ->where('id', $item->id)
                ->increment('stock', $quantity);
        }
    }
}
