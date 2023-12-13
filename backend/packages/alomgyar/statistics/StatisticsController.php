<?php

namespace Alomgyar\Statistics;

use Alomgyar\Shops\Shop;
use Alomgyar\Subcategories\Subcategory;
use Alomgyar\Suppliers\Supplier;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatisticsController extends Controller
{
    private $statistics;

    //
    public function __construct()
    {
        $hasRun = Schema::hasTable('migrations') && DB::table('migrations')->where('migration', '2021_06_07_142420_create_shipping_methods_table')->exists();
        if ($hasRun) {
            $this->statistics = new StatisticsExport;
        }
    } //end func: __construct

    public function index()
    {
        //$model = Recommender::latest()->paginate(25);

        $shops = Shop::where('status', 1)->get();
        $paymentMethods = DB::table('payment_methods')->whereNull('deleted_at')->get();
        $subcategories = Subcategory::get();
        $suppliers = Supplier::get();

        return view('statistics::index', [
            'shops' => $shops,
            'paymentMethods' => $paymentMethods,
            'subcategories' => $subcategories,
            'suppliers' => $suppliers,
        ]);
    }

    //
    public function generateTraffic(Request $request)
    {
        $selectedType = in_array($request->input('type'), [1, 2, 3]) ? $request->input('type') : 1;
        $params = $request->input();

        switch ($selectedType) {
            case 1:
                $filename = 'Alomgyar-forgalom-statisztika-'.date('Y-m-d').'.xls';
                $rows = $this->statistics->getRowsForStaistics($params['filter'] ?? null);
                break;

            case 2:
                $filename = 'Alomgyar-legtobbet-eladott-konyvek-'.date('Y-m-d').'.xls';
                $rows = $this->statistics->getRowsMostOrdered($params['filter'] ?? null);
                break;

            case 3:
                $filename = 'Alomgyar-legtobbet-elojegyzett-konyvek-'.date('Y-m-d').'.xls';
                $rows = $this->statistics->getRowsMostPreOrdered($params['filter'] ?? null);
                break;
        }

        $sums = $this->statistics->calculateSums($rows);
        $sumsRow = [
            '',
            'ÖSSZESEN',
        ];
        foreach ($sums as $sum) {
            $sumsRow[] = $sum;
        }
        $rowsToExcel = [$sumsRow];
        $rowsToExcel[] = $this->statistics->getHeadings();
        foreach ($rows as $row) {
            $rowsToExcel[] = $row;
        }

        return (new OrderExport($rowsToExcel, []))->download($filename);
    } //end func: generateTraffic

    //
    public function generateProducts(Request $request)
    {
        $params = $request->input();

        $rows = $this->statistics->withFilters($params['filter'])->getRowsForProducts();

        $filename = 'Alomgyar-cikktorzs-'.date('Y-m-d').'.xlsx';

        return (new OrderExport($rows, $this->statistics->getHeadings(2)))->download($filename);
    } //end func: generateProducts
}
