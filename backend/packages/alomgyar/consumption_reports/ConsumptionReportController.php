<?php

namespace Alomgyar\Consumption_reports;

use Alomgyar\Consumption_reports\Jobs\AuthorConsumptionMonthReportGenerateJob;
use Alomgyar\Consumption_reports\Jobs\ConsumptionMonthReportGenerateJob;
use Alomgyar\Consumption_reports\Jobs\LegalOwnerConsumptionMonthReportGenerateJob;
use Alomgyar\Consumption_reports\Reports\GeneralConsumptionReport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConsumptionReportController extends Controller
{
    public function index()
    {
        return view('consumption_reports::index');
    }

    public function show()
    {
        return view('consumption_reports::show');
    }

    public function generateTest()
    {
        $startDate = date('Y-m-d', strtotime('First day of this month')).' 00:00:00';
        $endDate = date('Y-m-d', strtotime('Last day of this month')).' 23:59:59';
        GeneralConsumptionReport::getConsumptionReport($startDate, $endDate, false);

        return redirect()->back()->with('flash_message', 'Teszt jelentés generálás sikeres volt!');
    }

    public function authorRegenerate(Request $request)
    {
        $startMonth = $request->get('startMonth', Carbon::now()->subMonth()->format('Y-m'));
        $endMonth = $request->get('selectedMonth', Carbon::now()->subMonth()->format('Y-m'));

        $startDate = Carbon::createFromFormat('Y-m', $startMonth)->startOfMonth()->format('Y-m-d H:i:s');
        $endDate = Carbon::createFromFormat('Y-m', $endMonth)->endOfMonth()->format('Y-m-d H:i:s');

        //Artisan::call('report:author-consumption');
        ConsumptionMonthReportGenerateJob::dispatch($startDate, $endDate);
        AuthorConsumptionMonthReportGenerateJob::dispatch($startDate, $endDate);

        return redirect()->route('consumption_report.index')->with('success', $startDate.' - '.$endDate.' havi Szerzői fogyásjelentés újra generálás folyamatban!');
    }

    public function legalRegenerate(Request $request)
    {
        $startMonth = $request->get('startMonth', Carbon::now()->subMonth()->format('Y-m'));
        $endMonth = $request->get('selectedMonth', Carbon::now()->subMonth()->format('Y-m'));

        $startDate = Carbon::createFromFormat('Y-m', $startMonth)->startOfMonth()->format('Y-m-d H:i:s');
        $endDate = Carbon::createFromFormat('Y-m', $endMonth)->endOfMonth()->format('Y-m-d H:i:s');

        ConsumptionMonthReportGenerateJob::dispatch($startDate, $endDate);
        LegalOwnerConsumptionMonthReportGenerateJob::dispatch($startDate, $endDate);

        return redirect()->route('consumption_report.index')->with('success', $startDate.' - '.$endDate.' időintervallumú Jogtulajdonos fogyásjelentés újra generálás folyamatban!');
    }
}
