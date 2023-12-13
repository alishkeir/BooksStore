<?php

namespace Alomgyar\Consumption_reports\Reports;

use Alomgyar\Consumption_reports\ConsumptionReport;
use Alomgyar\Consumption_reports\Jobs\ConsumptionMonthReportExcelGenerateJob;
use Alomgyar\Consumption_reports\Jobs\ConsumptionMonthReportUpdateRemainingQuantityJob;
use Alomgyar\Product_movements\ProductMovement;
use Alomgyar\Products\Product;
use App\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeneralConsumptionReport implements ConsumptionReportInterface
{
    protected static $report;

    protected static $startDate;

    protected static $endDate;

    protected static $period;

    protected static bool $reportOnly = false;

    protected static int $numberOfBooks = 0;

    public static function getConsumptionReport($startDate = null, $endDate = null, bool $reportOnly = false)
    {
        $startPeriod = Carbon::createFromDate($startDate)->format('Y-m');
        $endPeriod = Carbon::createFromDate($endDate)->format('Y-m');

        if ($startPeriod == $endPeriod) {
            self::$period = $startPeriod;
        } else {
            self::$period = $startPeriod.'_'.$endPeriod;
        }

        self::$startDate = $startDate ?? date('Y-m-d', strtotime('First day of last month')).' 00:00:00';
        self::$endDate = $endDate ?? date('Y-m-d', strtotime('Last day of last month')).' 23:59:59';
        //self::$period    = Carbon::createFromDate($startDate)->format('Y-m');
        //self::$period    = Carbon::createFromDate($startDate)->format('Y-m'). '_'.Carbon::createFromDate($endDate)->format('Y-m');
        self::$report = collect([]);
        self::$reportOnly = $reportOnly;
        self::$numberOfBooks = 0;

        $consumptions = self::getConsumptions();
        $ebookConsumptions = self::getEbookConsumptions();
        self::consumptionData($consumptions);
        self::ebookConsumptionData($ebookConsumptions);

        $grouped = self::$report->groupBy([
            'supplier_id', function ($item) {
                return $item['product_id'];
            },
        ], true);

        $sum = self::getSums($grouped);

        self::$report = collect($sum)->map(function ($item) {
            return collect($item);
        });

        if ($reportOnly) {
            return self::$report;
        } else {
            return self::createExcelFiles();
        }
    }

    public static function getConsumptions(): Collection
    {
        $allTotalSalesInThisPeriod = DB::select(DB::raw("select `product_movements_items`.`product_id`, SUM(`stock_out`) as total_sales
                    from `product_movements_items`
                    where `product_movements_items`.`product_movements_id` in (
                        select `id` from `product_movements`
                        where `created_at` between '".self::$startDate."' and '".self::$endDate."'
                        and `destination_type` in (".ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER.','.ProductMovement::DESTINATION_TYPE_SHOP_ORDER.")
                        and `source_type` != 'storno' and `is_canceled` = 0
                    )
                    and (`product_movements_items`.`remaining_quantity_from_report` is null OR `product_movements_items`.`remaining_quantity_from_report` > 0)
                    group by `product_movements_items`.`product_id`"));

        if (empty($allTotalSalesInThisPeriod)) {
            return collect([]);
        }

        $selectedProductMovementsInThisPeriod = DB::select(DB::raw('
            select `product_movements`.`id`
            from `product_movements` where exists (
                select * from `product_movements_items` where `product_movements`.`id` = `product_movements_items`.`product_movements_id`
                and `product_id` in ('.collect($allTotalSalesInThisPeriod)->pluck('product_id')->join(', ').")
                and (`remaining_quantity_from_report` is null or `remaining_quantity_from_report` > 0)
                and `product_movements_items`.`deleted_at` is null
            )
            and `created_at` <= '".self::$endDate."'
            and `source_type` != 'storno' and `is_canceled` = 0
            and `destination_type` = ".ProductMovement::DESTINATION_TYPE_ACQUISITION.'
            and `product_movements`.`deleted_at` is null;
        '));

        $productMovements = collect($selectedProductMovementsInThisPeriod)->pluck('id')->join(',');
        $pam = DB::select(DB::raw("select `id` from `suppliers` where `title` LIKE '%Publish and More%'"));
        $soldItems = DB::select(DB::raw("select `product_movements_items`.`id`,`product_movements_items`.`product_id`, `product_movements_items`.`purchase_price`,
            `product_movements`.`source_id` as supplier_id,
            `suppliers`.`percent`, `suppliers`.`title` as `supplier_name`,
            `sales`.`total_sales`, `income`.`total_income`, `product`.`tax_rate`, `product_price`.`price_list`, `product_price`.`price_list_original`,
            `product_movements_items`.`stock_in`, `product_movements_items`.`remaining_quantity_from_report`,
            `product`.`title` as `product_title`, `product`.`isbn`
            from `product_movements_items`
            inner join `product_movements` on `product_movements_items`.`product_movements_id` = `product_movements`.`id`
            inner join `suppliers` on `product_movements`.`source_id` = `suppliers`.`id`
            inner join (
                select `product_movements_items`.`product_id`, SUM(`stock_out`) as total_sales
                from `product_movements_items`
                where `product_movements_items`.`product_movements_id` in (
                    select `id` from `product_movements`
                    where `created_at` between '".self::$startDate."' and '".self::$endDate."'
                    and `destination_type` in (".ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER.','.ProductMovement::DESTINATION_TYPE_SHOP_ORDER.")
                    and `source_type` != 'storno' and `is_canceled` = 0
                    )
                group by `product_movements_items`.`product_id`
            ) as sales on `product_movements_items`.`product_id` = `sales`.`product_id`
            inner join (
                select `order_items`.`product_id`, SUM(`price`) as total_income
                from `order_items`
                join `orders` on `orders`.`id` = `order_items`.`order_id`
                where `order_items`.`created_at` and `orders`.`status` >= ".Order::STATUS_WAITING_FOR_SHIPPING.' and `orders`.`status` <= '.Order::STATUS_COMPLETED."
                and `orders`.`id` in (
                    select `destination_id` from `product_movements`
                        where `created_at` between '".self::$startDate."' and '".self::$endDate."'
                        and `destination_type` in (".ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER.','.ProductMovement::DESTINATION_TYPE_SHOP_ORDER.")
                        and `source_type` != 'storno' and `is_canceled` = 0
                )
                group by `order_items`.`product_id`
            ) as income on `product_movements_items`.`product_id` = `income`.`product_id`
            inner join `product` on `product`.`id` = `product_movements_items`.`product_id`
            inner join `product_price` on `product`.`id` = `product_price`.`product_id` and `product_price`.`store` = 0
            where `product_movements_id` in (".$productMovements.')
            and `sales`.`total_sales` is not null
            group by `id`, `product_id`, `purchase_price`, `source_id`, `percent`, `total_sales`, `tax_rate`, `price_list`, `total_income`
            order by `product_id` ASC, `percent` ASC;'));

        return collect($soldItems)->transform(function ($item) use ($pam) {
            if ($pam[0]->id === $item->supplier_id) {
                if ($item->total_sales > 0) {
                    $item->purchase_price = (int) $item->total_income / (int) $item->total_sales;
                    $item->total_sales = (int) $item->total_sales;
                    $item->percent = 0;
                    if ($item->total_sales > 0) {
                        return $item;
                    }
                }
            } else {
                if (! $item->purchase_price) {
                    $productPrice = $item->price_list ?? $item->price_list_original;
                    if ($productPrice) {
                        $netPrice = (int) $productPrice / (1 + ((int) $item->tax_rate / 100));
                        $item->purchase_price = (int) $netPrice * ($item->percent / 100);
                    }
                }
                $item->total_sales = (int) $item->total_sales;
                if ($item->total_sales > 0) {
                    return $item;
                }
            }
        });
    }

    public static function getEbookConsumptions(): Collection
    {
        $allTotalEbookSalesInThisPeriod = DB::select(DB::raw("select `order_items`.`product_id`, SUM(`quantity`) as total_sales
                    from `order_items`
                    left join `product` on `product`.`id` = `order_items`.`product_id`
                    where `order_items`.`created_at` between '".self::$startDate."' and '".self::$endDate."'
                    and `product`.`type` = ".Product::EBOOK.'
                    group by `order_items`.`product_id`'));

        if (empty($allTotalEbookSalesInThisPeriod)) {
            return collect([]);
        }

        $productIDs = collect($allTotalEbookSalesInThisPeriod)->pluck('product_id')->join(',');

        $soldItems = DB::select(DB::raw("select `order_items`.`product_id`,
            `suppliers`.`percent`, `suppliers`.`title` as `supplier_name`, `suppliers`.`id` as `supplier_id`,
            `sales`.`total_sales`, `product`.`tax_rate`, `product_price`.`price_list`, `product_price`.`price_list_original`,
            `product`.`title` as `product_title`, `product`.`isbn`
            from `order_items`
            inner join `suppliers` on `suppliers`.`title` LIKE '%dibook%'
            inner join (
                select `order_items`.`product_id`, SUM(`quantity`) as total_sales
                from `order_items`
                where `order_items`.`created_at` between '".self::$startDate."' and '".self::$endDate."'
                group by `order_items`.`product_id`
            ) as sales on `order_items`.`product_id` = `sales`.`product_id`
            inner join `product` on `product`.`id` = `order_items`.`product_id`
            inner join `product_price` on `product`.`id` = `product_price`.`product_id` and `product_price`.`store` = 0
            where `order_items`.`product_id` in (".$productIDs.')
            and `sales`.`total_sales` is not null
            group by `product_id`, `percent`, `total_sales`, `tax_rate`, `price_list`, `supplier_name`, `supplier_id`
            order by `product_id` ASC, `percent` ASC'));

        return collect($soldItems)->transform(function ($item) {
            $productPrice = $item->price_list ?? $item->price_list_original;
            $netPrice = 0;
            $item->purchase_price = 0;
            if ($productPrice) {
                $netPrice = (int) $productPrice / (1 + ((int) $item->tax_rate / 100));
                $item->purchase_price = (int) $netPrice * ($item->percent / 100);
            }

            $item->total_sales = (int) $item->total_sales;
            if ($item->total_sales > 0) {
                return $item;
            }
        });
    }

    public static function consumptionData($consumptions): void
    {
        $skippableProductID = 0;
        $pendingQuantity = [];

        foreach ($consumptions as $item) {
            if ($item && $skippableProductID !== $item?->product_id) {
                $originalRemainingQuantity = $item->remaining_quantity_from_report ?? $item->stock_in;

                if (! empty($pendingQuantity) && isset($pendingQuantity['product_id']) && $pendingQuantity['product_id'] == $item->product_id) {
                    $remainingQuantity = $originalRemainingQuantity - $pendingQuantity['quantity'];
                } else {
                    $remainingQuantity = $originalRemainingQuantity - $item->total_sales;
                }

                if ($remainingQuantity >= 0) {
                    if (isset($pendingQuantity['quantity']) && $pendingQuantity['quantity'] > 0) {
                        $total_sales = $pendingQuantity['product_id'] == $item?->product_id ? $pendingQuantity['quantity'] : $item->total_sales;
                        $consumptionData = [
                            'period' => self::$period,
                            'supplier_id' => $item->supplier_id,
                            'supplier_name' => $item->supplier_name,
                            'percent' => $item->percent,
                            'product_id' => $item->product_id,
                            'product_title' => $item->product_title,
                            'isbn' => $item->isbn,
                            'total_sales' => $total_sales,
                            'purchase_price' => $item->purchase_price,
                            'price_list' => $item->price_list ?? $item->price_list_original,
                            'total_amount' => $total_sales * $item->purchase_price,
                        ];

                        self::$report->push($consumptionData);

                        if (! self::$reportOnly) {
                            //
                            //ConsumptionMonthReportUpdateRemainingQuantityJob::dispatch($item->id, $remainingQuantity);
                            //DB::table('product_movements_items')->where('id', $this->itemId)->update(['remaining_quantity_from_report' => $this->remainingQuantity]);
                            DB::statement("UPDATE product_movements_items SET remaining_quantity_from_report = {$remainingQuantity} WHERE id = {$item->id}");
                        }
                    }
                    $skippableProductID = $item->product_id;
                } else {
                    $pendingQuantity = [
                        'product_id' => $item->product_id,
                        'quantity' => abs($remainingQuantity),
                    ];
                    if ($item->total_sales > 0) {
                        if ($originalRemainingQuantity == 0) {
                            $consumptionData = [
                                'period' => self::$period,
                                'supplier_id' => 0,
                                'supplier_name' => 'Hiányzik',
                                'percent' => $item->percent,
                                'product_id' => $item->product_id,
                                'product_title' => $item->product_title,
                                'isbn' => $item->isbn,
                                'total_sales' => abs($remainingQuantity),
                                'purchase_price' => $item->purchase_price,
                                'price_list' => $item->price_list ?? $item->price_list_original,
                                'total_amount' => $originalRemainingQuantity * $item->purchase_price,
                            ];

                            self::$report->push($consumptionData);
                        } else {
                            $consumptionData = [
                                'period' => self::$period,
                                'supplier_id' => $item->supplier_id,
                                'supplier_name' => $item->supplier_name,
                                'percent' => $item->percent,
                                'product_id' => $item->product_id,
                                'product_title' => $item->product_title,
                                'isbn' => $item->isbn,
                                'total_sales' => $originalRemainingQuantity,
                                'purchase_price' => $item->purchase_price,
                                'price_list' => $item->price_list ?? $item->price_list_original,
                                'total_amount' => $originalRemainingQuantity * $item->purchase_price,
                            ];

                            self::$report->push($consumptionData);

                            if (! self::$reportOnly) {
                                //ConsumptionMonthReportUpdateRemainingQuantityJob::dispatch($item->id, 0);
                                DB::statement("UPDATE product_movements_items SET remaining_quantity_from_report = 0 WHERE id = {$item->id}");
                            }
                        }
                    }
                }
            }
        }
    }

    public static function ebookConsumptionData($consumptions)
    {
        foreach ($consumptions as $item) {
            if ($item) {
                self::$report->push([
                    'period' => self::$period,
                    'supplier_id' => $item->supplier_id ?? null,
                    'supplier_name' => $item->supplier_name ?? null,
                    'percent' => $item->percent ?? null,
                    'product_id' => $item->product_id,
                    'product_title' => $item->product_title,
                    'isbn' => $item->isbn,
                    'total_sales' => $item->total_sales,
                    'purchase_price' => $item->purchase_price ?? 0,
                    'price_list' => $item->price_list ?? $item->price_list_original,
                    'total_amount' => $item->total_sales * $item->purchase_price,
                ]);
            }
        }
    }

    public static function getSums($grouped): array
    {
        $sum = [];

        foreach ($grouped as $supplier_id => $items) {
            foreach ($items as $product_id => $item) {
                if (array_sum(array_column($item->toArray(), 'total_sales')) > 0) {
                    $data = [
                        'supplier_id' => $supplier_id,
                        'product_id' => $product_id,
                        'product_title' => $item->first()['product_title'],
                        'isbn' => $item->first()['isbn'],
                        'total_sales' => array_sum(array_column($item->toArray(), 'total_sales')),
                        'purchase_price' => $item->first()['purchase_price'],
                        'price_list' => $item->first()['price_list'],
                        'total_amount' => array_sum(array_column($item->toArray(), 'total_amount')),
                    ];
                    $sum[$supplier_id][$product_id] = collect($data);
                }

                $sum[$supplier_id]['details'] = [
                    'period' => $item->first()['period'],
                    'supplier_name' => $item->first()['supplier_name'],
                    'percent' => $item->first()['percent'],
                ];
            }
        }

        return $sum;
    }

    public static function createExcelFiles()
    {
        ConsumptionMonthReportExcelGenerateJob::dispatch(self::$report, self::$numberOfBooks, self::$period);

        //self::saveReport($files);
    }

    public static function saveReport(array $files): bool
    {
        ConsumptionReport::updateOrCreate(
            ['period' => self::$period],
            [
                'number_of_books' => self::$numberOfBooks,
                'number_of_suppliers' => self::$report->count(),
                'link_to_report' => $files,
            ]
        );

        return true;
    }
}
