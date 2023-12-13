<?php

namespace App\Exports;

use Alomgyar\InventoryExport\InventoryZero;
use Alomgyar\Warehouses\Inventory;
use Alomgyar\Warehouses\Warehouse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $warehouseID;

    public function __construct($warehouseID)
    {
        $this->warehouseID = $warehouseID;
    }

    public function collection()
    {
        $collect = collect([]);
        $query = InventoryZero::with(['product' => function ($q) {
            $q->select('product.id', 'product.title', 'product.isbn', 'product.publisher_id', 'beszallitok.neve')
                ->leftJoin(DB::raw('(select  `product`.`title`, `product`.`isbn`, GROUP_CONCAT(DISTINCT `supplier`.`title` SEPARATOR \', \') as neve
            from `product`
            left join `product_movements_items`on `product_movements_items`.`product_id` = `product`.`id`
            inner join (select `product_movements`.`id`, `title` from `suppliers`
            inner join `product_movements` on `product_movements`.`source_id` = `suppliers`.`id`
            where `product_movements`.`destination_type` = 3) as supplier on `supplier`.`id` = `product_movements_items`.`product_movements_id`
            where `product`.`status` = 1 and `product`.`deleted_at` is null
            group by `product`.`title`, `product`.`isbn`) as beszallitok'), function ($join) {
                    $join->on('beszallitok.isbn', '=', 'product.isbn');
                });
        }, 'product.publisher:title'])->active();

        if ($this->warehouseID == -1) {
            $shouldContain = 'álomgyár könyvesbolt';
            $alomgyarWarehouseIDS = Warehouse::where('title', 'LIKE', "%$shouldContain%")->pluck('id')->toArray();
            $webShopID = Warehouse::firstWhere('title', 'LIKE', 'Webshop')->id;
            $alomgyarWarehouseIDS[] = $webShopID;
            $query->whereIn('warehouse_id', $alomgyarWarehouseIDS)->groupBy('product_id')->sum('stock');
        } else {
            $query->where('warehouse_id', $this->warehouseID);
        }

        $queryR = Inventory::with(['product' => function ($q) {
            $q->select('product.id', 'product.title', 'product.isbn', 'product.publisher_id', 'beszallitok.neve')
                ->leftJoin(DB::raw('(select  `product`.`title`, `product`.`isbn`, GROUP_CONCAT(DISTINCT `supplier`.`title` SEPARATOR \', \') as neve
            from `product`
            left join `product_movements_items`on `product_movements_items`.`product_id` = `product`.`id`
            inner join (select `product_movements`.`id`, `title` from `suppliers`
            inner join `product_movements` on `product_movements`.`source_id` = `suppliers`.`id`
            where `product_movements`.`destination_type` = 3) as supplier on `supplier`.`id` = `product_movements_items`.`product_movements_id`
            where `product`.`status` = 1 and `product`.`deleted_at` is null
            group by `product`.`title`, `product`.`isbn`) as beszallitok'), function ($join) {
                    $join->on('beszallitok.isbn', '=', 'product.isbn');
                });
        }, 'product.publisher:title']);

        if ($this->warehouseID == -1) {
            $shouldContain = 'álomgyár könyvesbolt';
            $alomgyarWarehouseIDS = Warehouse::where('title', 'LIKE', "%$shouldContain%")->pluck('id')->toArray();
            $webShopID = Warehouse::firstWhere('title', 'LIKE', 'Webshop')->id;
            $alomgyarWarehouseIDS[] = $webShopID;
            $queryR->whereIn('warehouse_id', $alomgyarWarehouseIDS)->groupBy('product_id')->sum('stock');
        } else {
            $queryR->where('warehouse_id', $this->warehouseID);
        }

        $currentInventory = $queryR->get()->keyBy('product_id');

        $query->get()->each(function ($item) use ($collect, $currentInventory) {
            if ($item->product ?? false && $item ?? false) {
                $collect->push(collect([
                    (string) $item->product->isbn ?? 'Hiányzó adat',
                    $item->product->title,
                    $currentInventory->get($item->product_id)->stock ?? 0,
                    $item->stock,
                    $item->product?->price(0)?->price_list,
                    $item->product?->price(0)?->price_list_original,
                    $item->product->neve ?? 'Hiányzó adat',
                    $item->product->publisher->title ?? 'Hiányzó adat',
                ]));

                return $collect;
            }
        });

        return $collect;
    }

    public function headings(): array
    {
        return [
            'ISBN',
            'CÍM',
            'REDSZER SZERINTI DB',
            'LESZÁMOLT',
            'LISTA ÁR',
            'BESZERZÉSI ÁR',
            'BESZÁLLÍTÓ',
            'KIADÓ',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
