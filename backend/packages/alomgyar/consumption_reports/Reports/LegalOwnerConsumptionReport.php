<?php

namespace Alomgyar\Consumption_reports\Reports;

use Alomgyar\Consumption_reports\ConsumptionReport;
use Alomgyar\Consumption_reports\Jobs\LegalOwnerConsumptionMonthReportExcelGenerateJob;
use Alomgyar\Product_movements\ProductMovement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LegalOwnerConsumptionReport implements ConsumptionReportInterface
{
    protected static $report;

    protected static $startDate;

    protected static $endDate;

    protected static $period;

    public static function getConsumptionReport($startDate = null, $endDate = null, bool $reportOnly = false): bool|Collection
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
        //self::$period    = Carbon::createFromDate($startDate)->format('Y-m'). '_'.Carbon::createFromDate($endDate)->format('Y-m');
        //self:::$period    = Carbon::createFromDate($startDate)->format('Y-m');
        self::$report = collect([]);

        $consumptions = self::getConsumptions();

        self::consumptionData($consumptions);

        $grouped = self::$report->groupBy([
            'legal_owner_id', function ($item) {
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
        $results = $sumItems = [];

        //        $soldItems = DB::select(DB::raw("SELECT `i`.`product_id`, `total_sales`, AVG(`total_income` / `total_sales`) as price,
        //            `product`.`title` as `product_title`, `product`.`isbn`, `product`.`legal_owner_id`, `product`.`tax_rate`,
        //            `l`.`title` as legal_owner_name, `l`.`commission`, `product_price`.`price_list`
        //                FROM (
        //                  select `o`.`product_id`, SUM(`o`.`total_sales`) as total_sales, SUM(`o`.`total_income`) as total_income
        //                    from (
        //                      select `product_movements_items`.`product_id`, SUM(`stock_out`) as total_sales, (`sale_price` * SUM(`stock_out`)) as total_income
        //                      from `product_movements_items`
        //                      join `product_movements` on `product_movements`.`id` = `product_movements_items`.`product_movements_id`
        //                      WHERE `product_movements_items`.`created_at` between '" . self::$startDate . "' and '" . self::$endDate . "'
        //                      and (`destination_type` in (" . ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER . "," . ProductMovement::DESTINATION_TYPE_SHOP_ORDER . ") or `source_type` = 'merchant')
        //                      and `source_type` != 'storno' and `is_canceled` = 0
        //                      group by `product_movements_items`.`product_id`, `product_movements_items`.`sale_price`, `product_movements_items`.`stock_out`
        //                    ) as o
        //                group by `o`.`product_id`
        //                ) as i
        //            inner join `product` on `product`.`id` = `i`.`product_id`
        //            inner join `legal_owners` as l on `product`.`legal_owner_id` = `l`.`id`
        //            inner join `product_price` on `product`.`id` = `product_price`.`product_id` and `product_price`.`store` = 0
        //            group by `i`.`product_id`, `product_price`.`price_list`
        //            order by `product_id` ASC"));
        $soldItems = DB::select(DB::raw("select `product_movements_items`.`product_id`, SUM(`stock_out`) as total_sales, (`sale_price` * SUM(`stock_out`)) as total_income,
                            `product`.`title` as `product_title`, `product`.`isbn`, `product`.`legal_owner_id`, `product`.`tax_rate`,
                            `l`.`title` as legal_owner_name, `l`.`commission` , `product_price`.`price_list`
                          from `product_movements_items`
                          join `product_movements` on `product_movements`.`id` = `product_movements_items`.`product_movements_id`
                          join `product` on `product`.`id` = `product_movements_items`.`product_id`
                          join `legal_owners` as l on `product`.`legal_owner_id` = `l`.`id`
                          join `product_price` on `product`.`id` = `product_price`.`product_id` and `product_price`.`store` = 0
                          WHERE `product_movements_items`.`created_at` between '".self::$startDate."' and '".self::$endDate."'
                          and (`destination_type` in (".ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER.','.ProductMovement::DESTINATION_TYPE_SHOP_ORDER.") or `source_type` = 'merchant')
                          and `source_type` != 'storno' and `is_canceled` = 0
                          group by `product_movements_items`.`product_id`, `product_movements_items`.`sale_price`, `product_movements_items`.`stock_out`"));

        foreach ($soldItems ?? [] as $item) {
            $sumItems[$item->product_id]['total_sales'] = $sumItems[$item->product_id]['total_sales'] ?? 0;
            $sumItems[$item->product_id]['total_income'] = $sumItems[$item->product_id]['total_income'] ?? 0;
            $sumItems[$item->product_id]['product_id'] = $item->product_id;
            $sumItems[$item->product_id]['total_sales'] += $item->total_sales;
            $sumItems[$item->product_id]['total_income'] += $item->total_income;
            $sumItems[$item->product_id]['price'] = abs($sumItems[$item->product_id]['total_sales']) > 0 ? $sumItems[$item->product_id]['total_income'] / $sumItems[$item->product_id]['total_sales'] : $sumItems[$item->product_id]['total_income'];
            $sumItems[$item->product_id]['avg_price'] = abs($sumItems[$item->product_id]['total_sales']) > 0 ? $sumItems[$item->product_id]['total_income'] / $sumItems[$item->product_id]['total_sales'] : $sumItems[$item->product_id]['total_income'];
            $sumItems[$item->product_id]['product_title'] = $item->product_title;
            $sumItems[$item->product_id]['isbn'] = $item->isbn;
            $sumItems[$item->product_id]['legal_owner_name'] = $item->legal_owner_name;
            $sumItems[$item->product_id]['legal_owner_id'] = $item->legal_owner_id;
            $sumItems[$item->product_id]['commission'] = $item->commission;
            $sumItems[$item->product_id]['price_list'] = $item->price_list;
            $sumItems[$item->product_id]['tax_rate'] = $item->tax_rate;
        }

        foreach ($sumItems as $item) {
            $result = (object) $item;
            //            $result->percent    = $result->commission ?? 100;
            //            $result->commission = $result->price * $result->commission / 100;
            //dd($item);
            $result->commission = (int) $result->price_list / (1 + ((int) $item['tax_rate'] / 100));

            //            if ($item->total_sales > 0) {
            $results[] = $result;
            //            }
        }

        return collect($results);
    }

    public static function consumptionData($consumptions): void
    {
        foreach ($consumptions as $item) {
            if ($item) {
                self::$report->push([
                    'period' => self::$period,
                    'legal_owner_id' => $item->legal_owner_id ?? null,
                    'legal_owner_name' => $item->legal_owner_name ?? null,
                    'percent' => $item->percent ?? null,
                    'product_id' => $item->product_id,
                    'product_title' => $item->product_title,
                    'isbn' => $item->isbn,
                    'total_sales' => $item->total_sales,
                    'purchase_price' => $item->purchase_price ?? 0,
                    'avg_price' => $item->avg_price,
                    'price_list' => $item->price_list,
                    'total_amount' => $item->total_sales * $item->commission,
                    'commission' => $item->commission ?? 0,
                ]);
            }
        }
    }

    public static function getSums($grouped): array
    {
        $sum = [];
        foreach ($grouped as $ownerId => $items) {
            foreach ($items as $product_id => $item) {
                $data = [
                    'writer_id' => $ownerId,
                    'product_id' => $product_id,
                    'product_title' => $item->first()['product_title'],
                    'isbn' => $item->first()['isbn'],
                    'total_sales' => array_sum(array_column($item->toArray(), 'total_sales')),
                    'commission' => $item->first()['commission'],
                    'avg_price' => $item->first()['avg_price'],
                    'price_list' => $item->first()['price_list'],
                    'total_amount' => array_sum(array_column($item->toArray(), 'total_amount')),
                ];
                $sum[$ownerId][$product_id] = collect($data);
            }

            $sum[$ownerId]['details'] = [
                'period' => $item->first()['period'],
                'legal_owner_name' => $item->first()['legal_owner_name'],
                'percent' => $item->first()['percent'],
            ];
        }

        return $sum;
    }

    public static function createExcelFiles(): bool
    {
        LegalOwnerConsumptionMonthReportExcelGenerateJob::dispatch(self::$report, self::$period);

        return true;
    }

    public static function saveReport(array $files): bool
    {
        $generated = false;

        $consumptionReport = ConsumptionReport::where('period', self::$period)->first();

        if ($consumptionReport) {
            $updatedConsumptionReport = ConsumptionReport::where('period', self::$period)->update(['link_to_copyright_report' => $files]);
            $generated = true;
        } else {
            $createdConsumptionReport = ConsumptionReport::create([
                'period' => self::$period,
                'link_to_copyright_report' => $files,
                'number_of_books' => 0,
                'number_of_suppliers' => 0,
                'link_to_report' => [],
            ]);
            $generated = true;
        }

        return $generated;
    }
}
