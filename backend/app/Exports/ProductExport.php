<?php

namespace App\Exports;

use Alomgyar\Products\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public $filters;

    public function query()
    {
        if (($this->filters['only_book'] == 1) && ($this->filters['only_ebook'] == 1)) {
            $this->filters['only_book'] = 0;
            $this->filters['only_ebook'] = 0;
        }

        $model = Product::query()
        //->select('product.*', 'product_price.discount_percent')
        ->select('product.*')
        ->leftJoin(DB::raw('(select  `product`.`title`, `product`.`isbn`, GROUP_CONCAT(DISTINCT `supplier`.`title` SEPARATOR \', \') as neve
        from `product`
        left join `product_movements_items`on `product_movements_items`.`product_id` = `product`.`id`
        inner join (select `product_movements`.`id`, `title` from `suppliers`
        inner join `product_movements` on `product_movements`.`source_id` = `suppliers`.`id`
        where `product_movements`.`destination_type` = 3) as supplier on `supplier`.`id` = `product_movements_items`.`product_movements_id`
        where `product`.`status` = 1 and `product`.`deleted_at` is null
        group by `product`.`title`, `product`.`isbn`) as beszallitok'), function ($join) {
            $join->on('beszallitok.isbn', '=', 'product.isbn');
        })
        //->leftJoin('product_price', 'product.id', '=', 'product_price.product_id')->where('product_price.store', '=', 0)
        ->leftJoin('product_price as olcso', function ($join) {
            $join->on('product.id', '=', 'olcso.product_id');
            $join->on('olcso.store', '=', DB::raw('1'));
        })
        ->leftJoin('product_price as alom', function ($join) {
            $join->on('product.id', '=', 'alom.product_id');
            $join->on('alom.store', '=', DB::raw('0'));
        })
        ->leftJoin('product_price as nagyker', function ($join) {
            $join->on('product.id', '=', 'nagyker.product_id');
            $join->on('nagyker.store', '=', DB::raw('0'));
        })
        ->when($this->filters['only_selection'] ?? false, function ($query) {
            $query->whereIn('product.id', explode('-', $this->filters['selected_ids']));
        })

        ->when($this->filters['search'] ?? false, function ($query) {
            $query->search(trim($this->filters['search']));
        })
        ->when($this->filters['author'] ?? false, function ($query) {
            $query->withWhereHas('author', function ($query2) {
                $query2->where('author_id', '=', $this->filters['author']);
            });
        })
        ->when($this->filters['subcategory'] ?? false, function ($query) {
            $query->withWhereHas('subcategories', function ($query2) {
                $query2->where('subcategory_id', '=', $this->filters['subcategory']);
            });
        })
        ->when($this->filters['active'] ?? false, function ($query) {
            $query->where('product.status', 1);
        })
        ->when($this->filters['tax_rate'] ?? false, function ($query) {
            $query->where('tax_rate', $this->filters['tax_rate']);
        })
        ->when($this->filters['only_book'] == 1, function ($query) {
            $query->where('type', 0);
        })
        ->when($this->filters['only_ebook'] == 1, function ($query) {
            $query->where('type', 1);
        })
        ->when($this->filters['pre'] ?? false, function ($query) {
            $query->where('state', 1);
        })
        ->when($this->filters['normal'] ?? false, function ($query) {
            $query->where('state', 0);
        })
        ->when($this->filters['publisher'] ?? false, function ($query) {
            $query->where('publisher_id', $this->filters['publisher']);
        })
        ->when($this->filters['supplier'] ?? false, function ($query) {
            $query->join('product_movements_items', function ($join) {
                $join->on('product_movements_items.product_id', '=', 'product.id')
                     ->join('product_movements', function ($j) {
                         $j->on('product_movements_items.product_movements_id', '=', 'product_movements.id')
                           ->where('product_movements.destination_type', 3)
                           ->where('product_movements.source_id', $this->filters['supplier']);
                     });
            });
        })

        ->when($this->filters['discount_from'] != 0 || $this->filters['discount_to'] != 100, function ($query) {
            $query->where('product_price.discount_percent', '>=', $this->filters['discount_from']);
            $query->where('product_price.discount_percent', '<=', $this->filters['discount_to']);
        })
        ->when($this->filters['cart_price'] ?? false, function ($query) {
            $query->where('product_price.price_cart', '>', 0);
        })
        ->when($this->filters['warehouse'] ?? false, function ($query) {
            $query->join('inventories', 'product.id', '=', 'inventories.product_id')->where('inventories.stock', '>', 0);
            $query->where('inventories.warehouse_id', $this->filters['warehouse']);
        })
        ->when($this->filters['instock'] ?? false, function ($query) {
            $query->join('inventories as inventories_plus', ['product.id' => 'inventories_plus.product_id'])
            ->where('inventories_plus.stock', '>', 0)
            ->distinct();
        })
        ->when($this->filters['outofstock'] ?? false, function ($query) {
            $query->leftJoin('inventories as inventories_null', function ($join) {
                $join->on('product.id', '=', 'inventories_null.product_id')->where('inventories_null.stock', '>', 0);
            })->whereNull('inventories_null.product_id');
        })
        ->when($this->filters['lowstock'] ?? false, function ($query) {
            $query->join('inventories as inventories_low', ['product.id' => 'inventories_low.product_id'])
                  ->where('inventories_low.stock', '<=', 3)
                  ->where('inventories_low.warehouse_id', '=', $this->warehouses->where('type', 1)->first()->id)
                  ->where('product.is_stock_sensitive', 1)
                  ->distinct();
        })
        ->when($this->filters['cart_price'] ?? false, function ($query) {
            $query->where('alom.price_cart', '>', 0);
        });

        $exports = $model->select(
            'product.isbn',
            'product.title',
            'alom.price_list as alomlist',
            'alom.price_sale as alomsale',
            // DB::raw('null as alomnewlist'), // 'Álom ÚJ LISTA ÁR',
            // DB::raw('null as alomnew'), // 'Álom ÚJ ÁR',
            'olcso.price_list as olcsolist',
            'olcso.price_sale as olcsosale',
            // DB::raw('null as olcsonewlist'), // 'OK ÚJ LISTA ÁR',
            // DB::raw('null as olcsonew'), // 'OK ÚJ ÁR',
            'nagyker.price_list as nagykerlist',
            'nagyker.price_sale as nagykersale',
            // DB::raw('null as nagykernewlist'), // 'Nagyker ÚJ LISTA ÁR',
            // DB::raw('null as nagykernew'), // 'Nagyker ÚJ ÁR',
            'beszallitok.neve',
            'product.id'
        );

        return $exports;
    }

    public function withFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    public function headings(): array
    {
        return [
            'Isbn',
            'Cím',
            'Álomgyár',
            'Álom Akciós',
            // 'Álom ÚJ LISTA ÁR',
            // 'Álom ÚJ ÁR',
            'Olcsokönyvek',
            'OK Akciós',
            // 'OK ÚJ LISTA ÁR',
            // 'OK ÚJ ÁR',
            'Nagyker',
            'Nagyker Akciós',
            // 'Nagyker ÚJ LISTA ÁR',
            // 'Nagyker ÚJ ÁR',
            'Beszállítók',
            'Product ID',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            //'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
            'C' => ['font' => ['color' => ['rgb' => 'e62934']]],
            'D' => ['font' => ['color' => ['rgb' => 'e62934']]],
            'E' => ['font' => ['color' => ['rgb' => 'fbc72e']]],
            'F' => ['font' => ['color' => ['rgb' => 'fbc72e']]],
            'G' => ['font' => ['color' => ['rgb' => '4971ff']]],
            'H' => ['font' => ['color' => ['rgb' => '4971ff']]],
        ];
    }
}
