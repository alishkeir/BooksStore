<?php

namespace Alomgyar\Consumption_reports\Reports;

use Alomgyar\Consumption_reports\ConsumptionReport;
use Alomgyar\Consumption_reports\Jobs\AuthorConsumptionMonthReportExcelGenerateJob;
use Alomgyar\Products\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AuthorConsumptionReport implements ConsumptionReportInterface
{
    protected static $report;

    protected static $startDate;

    protected static $endDate;

    protected static $period;

    protected static $writerId;

    public static function getConsumptionReport($startDate = null, $endDate = null, bool $reportOnly = false, $writerId = null)
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

        self::$report = collect([]);
        self::$writerId = $writerId;

        $consumptions = self::getConsumptions();
        self::consumptionData($consumptions);

        $grouped = self::$report->groupBy([
            'writer_id', function ($item) {
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
        if (self::$writerId) {
            $authorIds = DB::table('author_writer')->select('author_id')->where('writer_id', self::$writerId)->get()->pluck('author_id');
            $writers = DB::table('writers')
                            ->select('id', 'title')
                            ->where('id', self::$writerId)
                            ->where(function ($query) {
                                $query->whereNull('deleted_at');
                                $query->orWhere('deleted_at', '>', self::$startDate);
                            })
                            ->get();
        } else {
            $authorIds = DB::table('author_writer')->select('author_id')->get()->pluck('author_id');
            $writers = DB::table('writers')->select('id', 'title')
                            ->where(function ($query) {
                                $query->whereNull('deleted_at');
                                $query->orWhere('deleted_at', '>', self::$startDate);
                            })
                            ->get();
        }
        $authorProducts = DB::table('product_author')->select('product_id')->whereIn('author_id', $authorIds->toArray())->get()->pluck('product_id');
        $products = Product::withoutGlobalScopes()->without('prices')->whereIn('id', $authorProducts)->get();
        $results = [];

        if ($authorProducts->isNotEmpty()) {
            $soldItems = DB::select(DB::raw("select `product_movements_items`.`product_id`, SUM(`stock_out`) as total_sales, (`sale_price` / (100 + `tax_rate`) * 100 * SUM(`stock_out`)) as total_income,
                            `product`.`title` as `product_title`, `product`.`isbn`, `product`.`writer_commission`, `product`.`tax_rate`
                          from `product_movements_items`
                          join `product_movements` on `product_movements`.`id` = `product_movements_items`.`product_movements_id`
                          join `product` on `product`.`id` = `product_movements_items`.`product_id`
                          WHERE `product_movements_items`.`created_at` between '".self::$startDate."' and '".self::$endDate."'
                          and `product_movements_items`.`product_id` in (".$authorProducts->join(',').")
                          and (`destination_type` in (1,2) or `source_type` = 'merchant')
                          and `source_type` != 'storno' and `is_canceled` = 0
                          group by `product_movements_items`.`product_id`, `product_movements_items`.`sale_price`, `product_movements_items`.`stock_out`"));

            $sumItems = [];
            if (empty($soldItems)) {
                return collect([]);
            }
            foreach ($soldItems ?? [] as $item) {
                $sumItems[$item->product_id]['total_sales'] = $sumItems[$item->product_id]['total_sales'] ?? 0;
                $sumItems[$item->product_id]['total_income'] = $sumItems[$item->product_id]['total_income'] ?? 0;
                $sumItems[$item->product_id]['product_id'] = $item->product_id;
                $sumItems[$item->product_id]['total_sales'] += $item->total_sales;
                $sumItems[$item->product_id]['total_income'] += $item->total_income;
                $sumItems[$item->product_id]['price'] = abs($sumItems[$item->product_id]['total_sales']) > 0 ? $sumItems[$item->product_id]['total_income'] / $sumItems[$item->product_id]['total_sales'] : $sumItems[$item->product_id]['total_income'];
                $sumItems[$item->product_id]['product_title'] = $item->product_title;
                $sumItems[$item->product_id]['isbn'] = $item->isbn;
                $sumItems[$item->product_id]['writer_commission'] = $item->writer_commission;
            }

            foreach ($sumItems ?? [] as $item) {
                $product = $products->where('id', $item['product_id'])->first();

                $bookAuthors = $product->author->pluck('id')->toArray();
                $itemWriters = DB::table('author_writer')->select('writer_id')->whereIn('author_id', $bookAuthors)->get()->pluck('writer_id');

                if ($itemWriters) {
                    foreach ($itemWriters as $writer) {
                        if ($writers->where('id', $writer)->first()) {
                            $result = (object) $item;
                            $commissionPercent = $result->writer_commission / $itemWriters->count();
                            $result->percent = $commissionPercent;
                            $result->writer_id = $writer;
                            $result->writer_name = $writers->where('id', $writer)->first()->title;
                            $result->author_commission = abs($result->price) * $commissionPercent / 100;

                            $results[] = $result;
                        }
                    }
                }
            }
        }

        return collect($results);
    }

    public static function consumptionData($consumptions): void
    {
        foreach ($consumptions as $item) {
            if ($item) {
                self::$report->push([
                    'period' => self::$period,
                    'writer_id' => $item->writer_id ?? null,
                    'writer_name' => $item->writer_name ?? null,
                    'percent' => $item->percent ?? null,
                    'product_id' => $item->product_id,
                    'product_title' => $item->product_title,
                    'isbn' => $item->isbn,
                    'total_sales' => $item->total_sales,
                    'purchase_price' => $item->purchase_price ?? 0,
                    'price_list' => $item->price,
                    'total_amount' => $item->total_sales * $item->author_commission,
                    'author_commission' => $item->author_commission ?? 0,
                ]);
            }
        }
    }

    public static function getSums($grouped): array
    {
        $sum = [];
        foreach ($grouped as $writerId => $items) {
            foreach ($items as $product_id => $item) {
                $data = [
                    'writer_id' => $writerId,
                    'product_id' => $product_id,
                    'product_title' => $item->first()['product_title'],
                    'isbn' => $item->first()['isbn'],
                    'total_sales' => array_sum(array_column($item->toArray(), 'total_sales')),
                    'author_commission' => $item->first()['author_commission'],
                    'price_list' => $item->first()['price_list'],
                    'total_amount' => array_sum(array_column($item->toArray(), 'total_amount')),
                ];
                $sum[$writerId][$product_id] = collect($data);

                $sum[$writerId]['details'] = [
                    'period' => $item->first()['period'],
                    'writer_name' => $item->first()['writer_name'],
                    'percent' => $item->first()['percent'],
                ];
            }
        }

        return $sum;
    }

    public static function createExcelFiles()
    {
        AuthorConsumptionMonthReportExcelGenerateJob::dispatch(self::$report, self::$period);

        return true;

        //self::saveReport($files);
    }

    // public static function saveReport(array $files): bool
    // {
    //     ConsumptionReport::where('period', self::$period)->update(['link_to_author_report' => $files]);

    //     return true;
    // }

    public static function saveReport(array $files): bool
    {
        $generated = false;

        $consumptionReport = ConsumptionReport::where('period', self::$period)->first();

        if ($consumptionReport) {
            $updatedConsumptionReport = ConsumptionReport::where('period', self::$period)->update(['link_to_author_report' => $files]);
            $generated = true;
        } else {
            $createdConsumptionReport = ConsumptionReport::create([
                'period' => self::$period,
                'link_to_author_report' => $files,
                'number_of_books' => 0,
                'number_of_suppliers' => 0,
                'link_to_report' => [],
            ]);
            $generated = true;
        }

        return $generated;
    }
}
