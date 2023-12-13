<?php

namespace App\Exports;

use Alomgyar\Product_movements\ProductMovement;
use Alomgyar\Products\Product;
use Alomgyar\Warehouses\Inventory;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplierStockExports implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    // 52 Líra Könyv Zrt - Saját
    protected $supplierId;

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    public function collection()
    {
        // GET PRODUCTS WITH THE GIVEN SUPPLIER
        $products = Product::query()
                ->select('id')
                ->with('productMovementItems.productMovement')
                ->whereHas('productMovementItems', function ($query) {
                    $query->whereHas('productMovement', function ($query2) {
                        $query2->where('source_id', $this->supplierId)
                            ->where('destination_type', ProductMovement::DESTINATION_TYPE_ACQUISITION);
                    });
                })
                ->with('inventories')
                ->whereHas('inventories', function ($query) {
                    $query->with('warehouse')
                        ->where('status', Inventory::STATUS_ACTIVE)
                        ->where('stock', '>', 0);
                })
                ->get();

        // GET STOCKS FOR THE GIVEN PRODUCTS
        $inventories = Inventory::query()
                ->with('warehouse:id,title', 'product:id,isbn,title')
                ->whereIntegerInRaw('product_id', $products->pluck('id'))
                ->where('status', Inventory::STATUS_ACTIVE)
                ->where('stock', '>', 0)
                ->get()
                ->groupBy('warehouse.title');

        // FORMAT DATA IN DESIRED WAY
        $stocks = [];
        foreach ($inventories as $warehouseTitle => $inventory) {
            foreach ($inventory as $key => $book) {
                $stocks[] = [
                    'isbn' => $book->product?->isbn,
                    'title' => $book->product?->title,
                    'warehouse_title' => $warehouseTitle,
                    'quantity' => $inventory->where('product_id', $book->product_id)->sum('stock'),
                ];
            }
        }

        // SORT BY BOOK TITLE
        return collect($stocks)->sortBy('title');
    }

    public function map($stocks): array
    {
        return [

            $stocks['isbn'],
            $stocks['title'],
            $stocks['warehouse_title'],
            $stocks['quantity'],

        ];
    }

    public function headings(): array
    {
        return [
            'Isbn',
            'Cím',
            'Raktár',
            'Készlet a raktárban',
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
