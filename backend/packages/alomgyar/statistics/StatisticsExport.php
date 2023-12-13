<?php

namespace Alomgyar\Statistics;

use Alomgyar\Products\Product;
use Alomgyar\Suppliers\Supplier;
use Illuminate\Support\Facades\DB;

class StatisticsExport
{
    private $shopShippingMethodId;

    //
    public function __construct()
    {
        $this->getshopShippingMethodId();
    } //end func: __construct

    //
    private function getshopShippingMethodId()
    {
        $item = DB::table('shipping_methods')->select('id')->where('method_id', 'shop')->orderByDesc('id')->first();
        if (! empty($item)) {
            $this->shopShippingMethodId = $item->id;
        }
    } //end func: getshopShippingMethodId

    //
    public function getRowsForStaistics($filter)
    {
        $rows = [];

        $period = $this->getPeriodFilter($filter);
        $startTime = $period['startTime'];
        $endTime = $period['endTime'];

        $preordersSub = DB::table('customer_preorders')->select(DB::raw('COUNT(*)'))->where('customer_preorders.product_id', '=', DB::raw('product.id'));
        if ($startTime) {
            $preordersSub = $preordersSub->where('customer_preorders.created_at', '>=', DB::raw('"'.$startTime.'"'));
        }
        if ($endTime) {
            $preordersSub = $preordersSub->where('customer_preorders.created_at', '<=', DB::raw('"'.$endTime.'"'));
        }

        $orderItems = DB::table('order_items')
        ->select(
            'order_items.product_id',
            'order_items.quantity',
            'order_items.total',
            'orders.attachments',
            'orders.invoice_url',
            'orders.store',
            'orders.shipping_method_id',
            DB::raw('product.title AS product_title'),
            'product.isbn',
            'product.tax_rate',
            DB::raw('('.$preordersSub->toSql().') AS preorders')
        )
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->join('product', 'product.id', '=', 'order_items.product_id')
        ->where('orders.status', '>', 2);
        if ($startTime) {
            $orderItems = $orderItems->where('orders.processed_at', '>=', $startTime);
        }
        if ($endTime) {
            $orderItems = $orderItems->where('orders.processed_at', '<=', $endTime);
        }
        if (isset($filter['is_shop']) && $filter['is_shop']) {
            $orderItems = $orderItems->where('orders.store', '=', 3);
        }
        if (isset($filter['is_webshop']) && $filter['is_webshop']) {
            $orderItems = $orderItems->where('orders.store', '!=', 3);
        }
        if (isset($filter['store']) && $filter['store'] && is_array($filter['store'])) {
            $orderItems = $orderItems->whereIn('orders.store', $filter['store']);
        }
        if (isset($filter['shop']) && $filter['shop']) {
            $orderItems = $orderItems->where('orders.shipping_data->shop->selected_shop->id', $filter['shop']);
        }
        if (isset($filter['pament_method']) && $filter['pament_method']) {
            $orderItems = $orderItems->where('orders.payment_method_id', $filter['pament_method']);
        }
        if (isset($filter['type']) && in_array($filter['type'], [0, 1])) {
            $orderItems = $orderItems->where('product.type', $filter['type']);
        }

        $orderItems = $orderItems->get();

        foreach ($orderItems as $item) {
            $hasInvoice = false;
            if (isset($item->attachments) && $item->attachments) {
                $attachments = json_decode($item->attachments);
                foreach ($attachments as $attachment) {
                    if ($item->invoice_url == $attachment) {
                        $hasInvoice = true;
                        break;
                    }
                }
            }

            if (! isset($rows[$item->product_id])) {
                $rows[$item->product_id] = [
                    'title' => $item->product_title,
                    'isbn' => $item->isbn,
                    'quantity' => 0,
                    'netto_sum' => 0,
                    'brutto_sum' => 0,
                    'invoice_num' => 0,
                    'invoice_brutto_sum' => 0,
                    'receipt_num' => 0,
                    'receipt_brutto_sum' => 0,
                    'quantity_in_shop' => 0,
                    'orders_in_shop' => 0,
                    'orders_in_alom' => 0,
                    'orders_in_olcso' => 0,
                    'orders_in_nagyker' => 0,
                    'preorders' => $item->preorders,
                ];
                $invoice[$item->product_id] = [];
            }

            $rows[$item->product_id]['quantity'] += $item->quantity;
            $rows[$item->product_id]['netto_sum'] += $item->total / (1 + ($item->tax_rate / 100));
            $rows[$item->product_id]['brutto_sum'] += $item->total;
            if ($hasInvoice) {
                $invoice[$item->product_id][$item->invoice_url] = true;
                $rows[$item->product_id]['invoice_num'] = count($invoice[$item->product_id]);
                //$rows[$item->product_id]['invoice_num']++;
                $rows[$item->product_id]['invoice_brutto_sum'] += $item->total;
            } else {
                $rows[$item->product_id]['receipt_num']++;
                $rows[$item->product_id]['receipt_brutto_sum'] += $item->total;
            }
            if ($item->store == 3) {
                $rows[$item->product_id]['orders_in_shop'] += $item->quantity;
            } elseif ($item->store == 0) {
                $rows[$item->product_id]['orders_in_alom'] += $item->quantity;
            } elseif ($item->store == 1) {
                $rows[$item->product_id]['orders_in_olcso'] += $item->quantity;
            } elseif ($item->store == 2) {
                $rows[$item->product_id]['orders_in_nagyker'] += $item->quantity;
            }
            if ($item->shipping_method_id == $this->shopShippingMethodId) {
                $rows[$item->product_id]['quantity_in_shop'] += $item->quantity;
            }
        }

        return $rows;
    } //end func: getRows

    //
    public function getRowsMostOrdered($filter)
    {
        DB::statement("SET SQL_MODE=''");
        $rows = [];

        $period = $this->getPeriodFilter($filter);
        $startTime = $period['startTime'];
        $endTime = $period['endTime'];

        $where = [];
        if ($startTime) {
            $where[] = ['orders.processed_at', '>=', $startTime];
        }
        if ($endTime) {
            $where[] = ['orders.processed_at', '<=', $endTime];
        }
        if (isset($filter['is_shop']) && $filter['is_shop']) {
            $where[] = ['orders.store', '=', 3];
        }
        if (isset($filter['is_webshop']) && $filter['is_webshop']) {
            $where[] = ['orders.store', '!=', 3];
        }
        if (isset($filter['shop']) && $filter['shop']) {
            $where[] = ['orders.shipping_data->shop->selected_shop->id', $filter['shop']];
        }
        if (isset($filter['pament_method']) && $filter['pament_method']) {
            $where[] = ['orders.payment_method_id', $filter['pament_method']];
        }

        $orderCountSub = DB::table('order_items AS oi2')->select(DB::raw('SUM(quantity)'))->join('orders', 'orders.id', '=', 'order_items.order_id')->where('product.id', '=', DB::raw('oi2.product_id'));
        if ($startTime) {
            //$orderCountSub = $orderCountSub->where('oi2.created_at', '>=', DB::raw('"'.$startTime.'"'));
            $orderCountSub = $orderCountSub->where('orders.processed_at', '>=', DB::raw('"'.$startTime.'"'));
        }
        if ($endTime) {
            //$orderCountSub = $orderCountSub->where('oi2.created_at', '<=', DB::raw('"'.$endTime.'"'));
            $orderCountSub = $orderCountSub->where('orders.processed_at', '<=', DB::raw('"'.$endTime.'"'));
        }
        $items = DB::table('product')
        ->select(
            'product.id',
            'product.title',
            'product.isbn',
            'product.tax_rate',
            DB::raw('('.$orderCountSub->toSql().') AS order_count')
        )
        ->join('order_items', 'order_items.product_id', '=', 'product.id')
        ->join('orders', 'orders.id', '=', 'order_items.order_id');
        foreach ($where as $cond) {
            $items = $items->where([$cond]);
        }
        if (isset($filter['store']) && $filter['store'] && is_array($filter['store'])) {
            $items = $items->whereIn('orders.store', $filter['store']);
        }
        if (isset($filter['type']) && in_array($filter['type'], [0, 1])) {
            $items = $items->where('product.type', $filter['type']);
        }
        $items = $items->orderByDesc('order_count')->groupBy('product.id')->limit(20);
        $items = $items->get();

        foreach ($items as $item) {
            $quantity = intval($item->order_count);

            $sumsAll = DB::table('order_items')
            ->select('order_items.total', 'order_items.quantity', 'orders.attachments', 'orders.invoice_url', 'orders.store', 'orders.shipping_method_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('product_id', $item->id);
            foreach ($where as $cond) {
                $sumsAll = $sumsAll->where([$cond]);
            }
            if (isset($filter['store']) && $filter['store'] && is_array($filter['store'])) {
                $sumsAll = $sumsAll->whereIn('orders.store', $filter['store']);
            }
            $sumsAll = $sumsAll->get();

            $nettoSum = 0;
            $invoiceNum = 0;
            $invoiceBruttoSum = 0;
            $quantityInShop = 0;
            $ordersInShop = 0;
            $ordersInWebAlom = 0;
            $ordersInWebOlcso = 0;
            $ordersInWebNagyker = 0;
            if ($sumsAll) {
                foreach ($sumsAll as $orderItem) {
                    $hasInvoice = false;
                    if (isset($orderItem->attachments) && $orderItem->attachments) {
                        $attachments = json_decode($orderItem->attachments);
                        foreach ($attachments as $attachment) {
                            if ($orderItem->invoice_url == $attachment) {
                                $hasInvoice = true;
                                break;
                            }
                        }
                    }

                    $nettoSum += $orderItem->total / (1 + ($item->tax_rate / 100));
                    if ($hasInvoice) {
                        $invoiceNum++;
                        $invoiceBruttoSum += $orderItem->total;
                    }
                    if ($orderItem->store == 3) {
                        $ordersInShop += $orderItem->quantity;
                    } elseif ($orderItem->store == 0) {
                        $ordersInWebAlom += $orderItem->quantity;
                    } elseif ($orderItem->store == 1) {
                        $ordersInWebOlcso += $orderItem->quantity;
                    } elseif ($orderItem->store == 2) {
                        $ordersInWebNagyker += $orderItem->quantity;
                    }
                    if ($orderItem->shipping_method_id == $this->shopShippingMethodId) {
                        $quantityInShop += $orderItem->quantity;
                    }
                }
            }
            $bruttoSum = intval($nettoSum * (1 + ($item->tax_rate / 100)));

            $preordersCount = DB::table('customer_preorders')->where('product_id', $item->id);
            if ($startTime) {
                $preordersCount = $preordersCount->where('created_at', '>=', $startTime);
            }
            if ($endTime) {
                $preordersCount = $preordersCount->where('created_at', '<=', $endTime);
            }
            $preordersCount = $preordersCount;
            $preordersCount = $preordersCount->count();

            $rows[$item->id] = [
                'title' => $item->title,
                'isbn' => $item->isbn,
                'quantity' => $quantity,
                'netto_sum' => intval($nettoSum),
                'brutto_sum' => $bruttoSum,
                'invoice_num' => $invoiceNum,
                'invoice_brutto_sum' => $invoiceBruttoSum,
                'receipt_num' => $quantity - $invoiceNum,
                'receipt_brutto_sum' => $bruttoSum - $invoiceBruttoSum,
                'quantity_in_shop' => $quantityInShop,
                'orders_in_shop' => $ordersInShop,
                'orders_in_alom' => $ordersInWebAlom,
                'orders_in_olcso' => $ordersInWebOlcso,
                'orders_in_nagyker' => $ordersInWebNagyker,
                'preorders' => $preordersCount,
            ];
        }

        return $rows;
    } //end func: getRowsMostOrdered

    //
    public function getRowsMostPreOrdered($filter)
    {
        DB::statement("SET SQL_MODE=''");
        $rows = [];

        $period = $this->getPeriodFilter($filter);
        $startTime = $period['startTime'];
        $endTime = $period['endTime'];

        $where = [];
        if ($startTime) {
            $where[] = ['orders.processed_at', '>=', $startTime];
        }
        if ($endTime) {
            $where[] = ['orders.processed_at', '<=', $endTime];
        }
        if (isset($filter['is_shop']) && $filter['is_shop']) {
            $where[] = ['orders.store', '=', 3];
        }
        if (isset($filter['is_webshop']) && $filter['is_webshop']) {
            $where[] = ['orders.store', '!=', 3];
        }
        if (isset($filter['shop']) && $filter['shop']) {
            $where[] = ['orders.shipping_data->shop->selected_shop->id', $filter['shop']];
        }
        if (isset($filter['pament_method']) && $filter['pament_method']) {
            $where[] = ['orders.payment_method_id', $filter['pament_method']];
        }

        $preOrderCountSub = DB::table('customer_preorders')->select(DB::raw('COUNT(*)'))->where('product.id', '=', DB::raw('customer_preorders.product_id'));
        if ($startTime) {
            $preOrderCountSub = $preOrderCountSub->where('customer_preorders.created_at', '>=', DB::raw('"'.$startTime.'"'));
        }
        if ($endTime) {
            $preOrderCountSub = $preOrderCountSub->where('customer_preorders.created_at', '<=', DB::raw('"'.$endTime.'"'));
        }
        $items = DB::table('product')
        ->select(
            'product.id',
            'product.title',
            'product.isbn',
            'product.tax_rate',
            DB::raw('('.$preOrderCountSub->toSql().') AS preorder_count')
        )
        ->join('order_items', 'order_items.product_id', '=', 'product.id')
        ->join('orders', 'orders.id', '=', 'order_items.order_id');
        foreach ($where as $cond) {
            $items = $items->where([$cond]);
        }
        if (isset($filter['store']) && $filter['store'] && is_array($filter['store'])) {
            $items = $items->whereIn('orders.store', $filter['store']);
        }
        if (isset($filter['type']) && in_array($filter['type'], [0, 1])) {
            $items = $items->where('product.type', $filter['type']);
        }
        $items = $items->orderByDesc('preorder_count')->groupBy('product.id')->limit(20);
        $items = $items->get();

        foreach ($items as $item) {
            $sumsAll = DB::table('order_items')
            ->select('order_items.total', 'order_items.quantity', 'orders.attachments', 'orders.invoice_url', 'orders.store', 'orders.shipping_method_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('product_id', $item->id);
            foreach ($where as $cond) {
                $sumsAll = $sumsAll->where([$cond]);
            }
            if (isset($filter['store']) && $filter['store'] && is_array($filter['store'])) {
                $sumsAll = $sumsAll->whereIn('orders.store', $filter['store']);
            }
            $sumsAll = $sumsAll->get();

            $quantity = 0;
            $nettoSum = 0;
            $invoiceNum = 0;
            $invoiceBruttoSum = 0;
            $quantityInShop = 0;
            $ordersInShop = 0;
            $ordersInWebAlom = 0;
            $ordersInWebOlcso = 0;
            $ordersInWebNagyker = 0;
            if ($sumsAll) {
                foreach ($sumsAll as $orderItem) {
                    $hasInvoice = false;
                    if (isset($orderItem->attachments) && $orderItem->attachments) {
                        $attachments = json_decode($orderItem->attachments);
                        foreach ($attachments as $attachment) {
                            if ($orderItem->invoice_url == $attachment) {
                                $hasInvoice = true;
                                break;
                            }
                        }
                    }

                    $quantity += $orderItem->quantity;
                    $nettoSum += $orderItem->total / (1 + ($item->tax_rate / 100));
                    if ($hasInvoice) {
                        $invoiceNum++;
                        $invoiceBruttoSum += $orderItem->total;
                    }
                    if ($orderItem->store == 3) {
                        $ordersInShop += $orderItem->quantity;
                    } elseif ($orderItem->store == 0) {
                        $ordersInWebAlom += $orderItem->quantity;
                    } elseif ($orderItem->store == 1) {
                        $ordersInWebOlcso += $orderItem->quantity;
                    } elseif ($orderItem->store == 2) {
                        $ordersInWebNagyker += $orderItem->quantity;
                    }
                    if ($orderItem->shipping_method_id == $this->shopShippingMethodId) {
                        $quantityInShop += $orderItem->quantity;
                    }
                }
            }
            $bruttoSum = intval($nettoSum * (1 + ($item->tax_rate / 100)));

            $rows[$item->id] = [
                'title' => $item->title,
                'isbn' => $item->isbn,
                'quantity' => $quantity,
                'netto_sum' => intval($nettoSum),
                'brutto_sum' => $bruttoSum,
                'invoice_num' => $invoiceNum,
                'invoice_brutto_sum' => $invoiceBruttoSum,
                'receipt_num' => $quantity - $invoiceNum,
                'receipt_brutto_sum' => $bruttoSum - $invoiceBruttoSum,
                'quantity_in_shop' => $quantityInShop,
                'orders_in_shop' => $ordersInShop,
                'orders_in_alom' => $ordersInWebAlom,
                'orders_in_olcso' => $ordersInWebOlcso,
                'orders_in_nagyker' => $ordersInWebNagyker,
                'preorders' => $item->preorder_count,
            ];
        }

        return $rows;
    } //end func: getRowsMostPreOrdered

    //
    public function calculateSums($rows)
    {
        $sums = [
            'quantity' => 0,
            'netto_sum' => 0,
            'brutto_sum' => 0,
            'invoice_num' => 0,
            'invoice_brutto_sum' => 0,
            'receipt_num' => 0,
            'receipt_brutto_sum' => 0,
            'quantity_in_shop' => 0,
            'orders_in_shop' => 0,
            'orders_in_alom' => 0,
            'orders_in_olcso' => 0,
            'orders_in_nagyker' => 0,
            'preorders' => 0,
        ];

        if ($rows) {
            foreach ($rows as $item) {
                $sums['quantity'] += $item['quantity'];
                $sums['netto_sum'] += $item['netto_sum'];
                $sums['brutto_sum'] += $item['brutto_sum'];
                $sums['invoice_num'] += $item['invoice_num'];
                $sums['invoice_brutto_sum'] += $item['invoice_brutto_sum'];
                $sums['receipt_num'] += $item['receipt_num'];
                $sums['receipt_brutto_sum'] += $item['receipt_brutto_sum'];
                $sums['quantity_in_shop'] += $item['quantity_in_shop'];
                $sums['orders_in_shop'] += $item['orders_in_shop'];
                $sums['orders_in_alom'] += $item['orders_in_alom'];
                $sums['orders_in_olcso'] += $item['orders_in_olcso'];
                $sums['orders_in_nagyker'] += $item['orders_in_nagyker'];
                $sums['preorders'] += $item['preorders'];
            }
        }

        return $sums;
    } //end func: calculateSums

    //
    public function getRowsForProducts()
    {
        DB::statement("SET SQL_MODE=''");
        $rows = [];

        $suppliers = Supplier::select('id', 'title')->get();
        $suppliersById = [];
        foreach ($suppliers as $supplier) {
            $suppliersById[$supplier->id] = $supplier->title;
        }

        $items = DB::table('product')->select(
            'product.id',
            'product.title',
            'product.isbn',
            'product.state',
            'product.tax_rate',
            DB::raw('(SELECT SUM(inventories.stock) FROM inventories WHERE inventories.product_id = product.id) AS stock'),
            DB::raw('(SELECT SUM(order_items.quantity) FROM order_items INNER JOIN orders ON orders.id = order_items.order_id WHERE order_items.product_id = product.id AND orders.status < 3 AND orders.store < 3) AS webshop_order_count'),
            DB::raw('publishers.title AS publisher_title'),
            'alom.price_list as alomlist',
            'alom.price_sale as alomsale',
            'olcso.price_list as olcsolist',
            'olcso.price_sale as olcsosale',
            'nagyker.price_list as nagykerlist',
            'nagyker.price_sale as nagykersale',
            DB::raw('(SELECT suppliers.id FROM product_movements_items INNER JOIN product_movements ON product_movements.id = product_movements_items.product_movements_id INNER JOIN suppliers ON suppliers.id = product_movements.source_id WHERE product_movements_items.product_id = product.id AND product_movements.source_type = "supplier" AND product_movements.destination_type = 3 AND product_movements.is_canceled = 0 AND (`remaining_quantity_from_report` != 0 or `remaining_quantity_from_report` is null) group by `suppliers`.`id`, `remaining_quantity_from_report`, `percent`
                order by `percent` ASC, `remaining_quantity_from_report` DESC LIMIT 1) AS suppliers_id'),
            DB::raw('(SELECT suppliers.percent FROM product_movements_items INNER JOIN product_movements ON product_movements.id = product_movements_items.product_movements_id INNER JOIN suppliers ON suppliers.id = product_movements.source_id WHERE product_movements_items.product_id = product.id AND product_movements.source_type = "supplier" AND product_movements.destination_type = 3 AND product_movements.is_canceled = 0 AND (`remaining_quantity_from_report` != 0 or `remaining_quantity_from_report` is null) group by `remaining_quantity_from_report`, `percent`
                order by `percent` ASC, `remaining_quantity_from_report` DESC LIMIT 1) AS percent'),
            DB::raw('(SELECT product_movements_items.purchase_price FROM product_movements_items INNER JOIN product_movements ON product_movements.id = product_movements_items.product_movements_id WHERE product_movements_items.product_id = product.id AND product_movements.source_type = "supplier" AND product_movements.destination_type = 3 AND product_movements.is_canceled = 0 ORDER BY product_movements_items.purchase_price DESC LIMIT 1) AS purchase_price')
        )
        ->leftJoin('publishers', 'publishers.id', '=', 'product.publisher_id');
        if ($this->filters['search'] ?? false) {
            $term = trim($this->filters['search']);
            $items = $items->where('product.id', 'like', '%'.$term.'%')
            ->orWhere('product.title', 'like', '%'.$term.'%')
            ->orWhere('product.isbn', 'like', '%'.$term.'%')
            ->orWhere('product.slug', 'like', '%'.$term.'%');
        }

        if ($this->filters['author'] ?? false) {
            $author = $this->filters['author'];
            $items = $items->where(function ($sub) use ($author) {
                $sub->select(DB::raw('COUNT(*)'))->from('product_author')->where('product_author.product_id', '=', DB::raw('product.id'))->where('product_author.author_id', '=', $author);
            }, '>', 0);
        }
        if ($this->filters['subcategory'] ?? false) {
            $subcategory = $this->filters['subcategory'];
            $items = $items->where(function ($sub) use ($subcategory) {
                $sub->select(DB::raw('COUNT(*)'))->from('product_subcategory')->where('product_subcategory.product_id', '=', DB::raw('product.id'))->where('product_subcategory.subcategory_id', '=', $subcategory);
            }, '>', 0);
        }
        if ($this->filters['active'] ?? false) {
            $items = $items->where('product.status', 1);
        }
        if ((isset($this->filters['only_book']) && $this->filters['only_book'] == 1) && (isset($this->filters['only_ebook']) && $this->filters['only_ebook'] == 1)) {
            $this->filters['only_book'] = 0;
            $this->filters['only_ebook'] = 0;
        }
        if (isset($this->filters['only_book']) && $this->filters['only_book'] == 1) {
            $items = $items->where('type', 0);
        }
        if (isset($this->filters['only_ebook']) && $this->filters['only_ebook'] == 1) {
            $items = $items->where('type', 1);
        }
        if ($this->filters['pre'] ?? false) {
            $items = $items->where('state', 1);
        }
        if ($this->filters['normal'] ?? false) {
            $items = $items->where('state', 0);
        }
        $items = $items->leftJoin('product_price', 'product.id', '=', 'product_price.product_id')->where('product_price.store', '=', 0);
        if ($this->filters['cart_price'] ?? false) {
            $items = $items->where('product_price.price_cart', '>', 0);
        }
        $items = $items->leftJoin('product_price as olcso', function ($join) {
            $join->on('product.id', '=', 'olcso.product_id');
            $join->on('olcso.store', '=', DB::raw('1'));
        });
        $items = $items->leftJoin('product_price as alom', function ($join) {
            $join->on('product.id', '=', 'alom.product_id');
            $join->on('alom.store', '=', DB::raw('0'));
        });
        $items = $items->leftJoin('product_price as nagyker', function ($join) {
            $join->on('product.id', '=', 'nagyker.product_id');
            $join->on('nagyker.store', '=', DB::raw('0'));
        });
        if ($this->filters['cart_price'] ?? false) {
            $items = $items->where('alom.price_cart', '>', 0);
        }

        $items = $items->whereNull('product.deleted_at')->groupBy('product.id')->get();

        if ($items) {
            foreach ($items as $item) {
                if ($this->filters['supplier'] ?? false) {
                    if ($item->suppliers_id != $this->filters['supplier']) {
                        continue;
                    }
                }

                $WebshopOrdersCount = intval($item->webshop_order_count);
                $suppliersTitle = $suppliersById[$item->suppliers_id] ?? '';

                $purchasePrice = $item->purchase_price;

                if (! $item->purchase_price) {
                    $productPrice = $item->alomlist;
                    if ($productPrice) {
                        $netPrice = (int) $productPrice / (1 + ((int) $item->tax_rate / 100));
                        $purchasePrice = (int) $netPrice * ($item->percent / 100);
                    }
                }

                $rows[$item->id] = [
                    'title' => $item->title,
                    'isbn' => $item->isbn,
                    'state' => ($item->state == 1) ? 'E' : 'N',
                    'stock' => $item->stock,
                    'reserved' => $WebshopOrdersCount,
                    'free' => $item->stock - $WebshopOrdersCount,
                    'publisher' => $item->publisher_title ?? '',
                    'alomprice' => $item->alomsale > 0 ? $item->alomsale : $item->alomlist,
                    'olcsoprice' => $item->olcsosale > 0 ? $item->olcsosale : $item->olcsolist,
                    'nagykerprice' => $item->nagykersale > 0 ? $item->nagykersale : $item->nagykerlist,
                    'supplier' => $suppliersTitle,
                    'purchase_price' => $purchasePrice,
                ];
            }
        }

        return $rows;
    } //end func: getRowsForProducts

    //
    private function getPeriodFilter($filter)
    {
        $startTime = false;
        $endTime = false;
        switch ($filter['period']) {
            case 'm1':
                $startTime = date('Y-m-d H:i:s', strtotime('-1 month'));
                break;

            case 'm2':
                $startTime = date('Y-m-d H:i:s', strtotime('-2 month'));
                break;

            case 'm3':
                $startTime = date('Y-m-d H:i:s', strtotime('-3 month'));
                break;

            case 'm6':
                $startTime = date('Y-m-d H:i:s', strtotime('-6 month'));
                break;

            case 'm12':
                $startTime = date('Y-m-d H:i:s', strtotime('-1 year'));
                break;

            case 'i':
                $startStamp = (isset($filter['from']) && $filter['from']) ? strtotime($filter['from']) : false;
                if ($startStamp) {
                    $startTime = date('Y-m-d H:i:s', $startStamp);
                }
                $endStamp = (isset($filter['to']) && $filter['to']) ? strtotime($filter['to']) : false;
                if ($endStamp) {
                    $endTime = date('Y-m-d H:i:s', $endStamp);
                }
                break;
        }

        return ['startTime' => $startTime, 'endTime' => $endTime];
    } //end func: getPeriodFilter

    //
    public function getHeadings($type = 1)
    {
        if ($type == 1) {
            $headings = [
                'Termék név',
                'isbn',
                'Eladott mennyiség',
                'Nettó össz.',
                'Bruttó össz.',
                'Számla db',
                'Számla bruttó',
                'Nyugta db',
                'Nyugta bruttó',
                'Boltban kiadott',
                'Bolti eladás',
                'Álomgyár eladás',
                'Olcsókönyvek eladás',
                'Nagyker eladás',
                'Előjegyzések száma',
            ];
        } elseif ($type == 2) {
            $headings = [
                'Termék név',
                'isbn',
                'Normál/előjegyezhető',
                'Raktáron',
                'Foglalt',
                'Szabad',
                'Kiadó',
                'ÁGY eladási ár',
                'ÓK eladási ár',
                'NK eladási ár',
                'Beszállító',
                'Beszerzési ár (HUF)',
            ];
        }

        return $headings;
    } //end func: getHeadings

    public function withFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }
}
