<?php

namespace Alomgyar\Orders;

use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Shops\Shop;
use Alomgyar\Warehouses\Warehouse;
use App\Exports\OrderExport;
use App\OrderItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListItemComponent extends Component
{
    use WithPagination;

    protected $listeners = ['setFilter'];

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'order_items.id';

    public $sortAsc = false;

    public $filters = [
        'shop' => false,
        'subcategory' => false,
        'cart_price' => false,
        'only_book' => false,
        'only_ebook' => false,
        'active' => 1,
        'shipping_method' => false,
        'payment_method' => false,
        'from' => false,
        'to' => false,
    ];

    public $data;

    public $selection = [];

    public $type;

    public function render()
    {
        $shipping_methods = ShippingMethod::all();
        $payment_methods = PaymentMethod::all();
        $shops = Shop::all();

        $model = $this->query()->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->perPage);

        foreach ($this->selection as $id => $selected) {
            if (! $selected) {
                unset($this->selection[$id]);
            }
        }

        return view('orders::components.listitemcomponent', [
            'model' => $model,
            'shipping' => $shipping_methods,
            'payment' => $payment_methods,
            'shops' => $shops,
        ]);
    }

    public function listItemTypeSum(string $type)
    {
        return $this->query($type);
    }

    protected function query($type = null)
    {
        if (! $type) {
            $type = $this->type;
        }

        $term = trim($this->s);
        $mainWarehouse = Warehouse::main();
        $model = OrderItem::query()
            ->select(
                'order_items.id',
                'order_items.order_id',
                'order_items.product_id',
                'order_items.price',
                'order_items.original_price',
                'order_items.quantity',
                'product.type',
                'product.isbn',
                'product.publisher_id'
            )
            ->leftJoin('product', ['product.id' => 'order_items.product_id'])
            ->leftJoin('orders', ['orders.id' => 'order_items.order_id'])
            ->leftJoin('inventories', ['product.id' => 'inventories.product_id'])
            ->where('product.type', 0)
            ->where('orders.status', '=', 1)
            ->search($term);

        if ($this->filters['payment_method'] ?? false) {
            $model = $model->where('orders.payment_method_id', $this->filters['payment_method']);
        }
        if ($this->filters['shipping_method'] ?? false) {
            $model = $model->where('orders.shipping_method_id', $this->filters['shipping_method']);
        }

        if ($this->filters['from'] ?? false) {
            $model->where('orders.created_at', '>', $this->filters['from']);
        }
        if ($this->filters['to'] ?? false) {
            $model->where('orders.created_at', '<', $this->filters['to']);
        }
        // Az egyik a teljesíthető rendelések: Itt azt nézzük hogy a GPS raktárban van-e
        // A másik a majdnem teljesíthető lista: Itt azt nézzük hogy melyik az ami nincs GPS-ben de máshol viszont igen
        // És van a nem teljesíthető rendelések: Ez meg az ha sehol sincs raktáron
        if (! empty($mainWarehouse) && isset($mainWarehouse->id)) {
            if ($type === 'ok') {
                $model = $model->where('inventories.warehouse_id', $mainWarehouse->id)->where('inventories.stock', '>', 0)->groupBy('order_items.id');
            }
            if ($type === 'almost') {
                //ami nincs GPS-ben (nem is volt)
                $model = $model->leftJoin('inventories as inventories_null', function ($join) use ($mainWarehouse) {
                    $join->on('product.id', '=', 'inventories_null.product_id')->where('inventories_null.warehouse_id', $mainWarehouse->id);
                })->whereNull('inventories_null.product_id')->groupBy('order_items.id');

                //ami nincs GPS-ben (volt de most nincs)
                $model->leftJoin('inventories as inventories_zero', ['product.id' => 'inventories_zero.product_id'])
                    ->where('inventories_zero.warehouse_id', '!=', $mainWarehouse->id)
                    ->where('inventories_zero.stock', '>', 0)->groupBy('order_items.id');

                // de máshol viszont igen
                $model = $model->where('inventories.warehouse_id', '!=', $mainWarehouse->id)->where('inventories.stock', '>', 0)->groupBy('order_items.id');
            }
        }
        if ($type === 'no') {
            $model = $model->where(function ($q) {
                $q->havingRaw('SUM(stock) <= ?', [0])->orWhereNull('inventories.product_id');
            });
        }

        return $model;
    }

    public function reverseAll()
    {
        $selectionOld = $this->selection;
        $this->selection = [];
        $model = $this->query();
        $model = $model->get();

        foreach ($model as $toSelect) {
            if (! isset($selectionOld[$toSelect->id])) {
                $this->selection[$toSelect->id] = true;
            } else {
                $this->selection[$toSelect->id] = false;
            }
        }
    }

    public function sortBy($column)
    {
        if ($this->sortField === $column) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $column;
    }

    public function setFilter($filter)
    {
        foreach ($filter as $key => $value) {
            if ($key == 'payment_method') {
                $this->filters['payment_method'] = $value;
            }
            if ($key == 'shipping_method') {
                $this->filters['shipping_method'] = $value;
            }
            if ($key == 'shop') {
                $this->filters['shop'] = $value;
            }
        }
    }

    public function generateXmlFromSelection()
    {
        $selection = $rows = [];
        foreach ($this->selection as $id => $sel) {
            array_push($selection, $id);
        }

        $term = trim($this->s);
        $orderItemsIDs = implode(',', $selection);
        $mainWarehouse = Warehouse::main();

        $model = DB::select(DB::raw(
            "
            select `order_items`.`id`, `order_items`.`product_id`, `product`.`title`, `product`.`isbn`, `orders`.`order_number`, `warehouse`.`title` as `warehouse_title`,
            `order_items`.`total`, `order_items`.`quantity`, `i`.`stock` as `warehouse_stock`, `gsp`.`stock` as `gsp_stock`, `publishers`.`title` as `publisher_title`,
            (select `s`.`title`
                from `product_movements_items`
                join `product_movements` as `pm` on `product_movements_items`.`product_movements_id` = `pm`.`id`
                join `suppliers` as `s` on `pm`.`source_id` = `s`.`id`
                where product_movements_items.product_id = `order_items`.`product_id`
                and `destination_type` = 3
                and `source_type` != 'storno' 
                and `is_canceled` = 0
                and (`remaining_quantity_from_report` != 0 or `remaining_quantity_from_report` is null)
                group by `s`.`title`, `remaining_quantity_from_report`, `percent`
                order by `percent` ASC, `remaining_quantity_from_report` DESC
                limit 1) as supplier_title
            from `order_items`
            join `product` on `product`.`id` = `order_items`.`product_id`
            join `orders` on `orders`.`id` = `order_items`.`order_id`
            left join `warehouse` on `warehouse`.`shop_id` = json_unquote(json_extract(`orders`.`shipping_data`, '$.\"shop\".\"selected_shop\".\"id\"'))
            left join `inventories` as `i` on `warehouse`.`id` = `i`.`warehouse_id` and `product`.`id` = `i`.`product_id`
            left join `inventories` as `gsp` on `product`.`id` = `gsp`.`product_id` and `gsp`.`warehouse_id` = {$mainWarehouse->id}
            left join `publishers` on `product`.`publisher_id` = `publishers`.`id`
            where `order_items`.`id` like '%{$term}%' and `product`.`type` = 0 and `orders`.`status` = 1
            and `order_items`.`id` in ({$orderItemsIDs})
            group by `order_items`.`id`, `order_items`.`total`, `order_items`.`quantity`, `warehouse`.`title`, `i`.`stock`, `gsp`.`stock`
            order by `order_items`.`id` desc"
        ));

        // dd($model);
        $select = ['title', 'isbn', 'warehouse_title', 'order_number', 'total', 'warehouse_stock', 'quantity', 'gsp_stock', 'supplier_title', 'publisher_title'];
        $heading = ['Termék cím', 'ISBN', 'A raktár, ahova beérkezik a rendelés (bolt pont megnevezése)', 'Rendelési szám', 'Eladási ár', 'jelenlegi készlete az aktuális raktárnak, ahova érkezett a rendelés', 'termékre most ennyi rendelés jött', 'GSP készlete', 'Beszállító', 'Kiadó'];

        foreach ($model as $row) {
            foreach ($select as $column) {
                $rows[$row->id][$column] = $row->{$column};
            }
        }

        return (new OrderExport($rows, $heading))->download('rendelesek-'.date('Y-m-d').'.xls');
    }
}
