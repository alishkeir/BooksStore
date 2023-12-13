<?php

namespace Alomgyar\Consumption_reports\Commands;

use Alomgyar\Consumption_reports\Reports\GeneralConsumptionReport;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ConsumptionReportCommand extends Command
{
    protected bool $reportOnly = false;

    protected $startDate;

    protected $endDate;

    protected $signature = 'report:consumption {startDate?} {endDate?} {--A|all}';

    protected $description = 'Create a monthly report on consumption of products by supplier';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->option('all')) {
            if ($this->argument('startDate') || $this->argument('endDate')) {
                $this->error('Az `all` kapcsolóval ne adj meg kezdő és/vagy végdátumot!');

                return CommandAlias::INVALID;
            }

            DB::table('product_movements_items')->update(['remaining_quantity_from_report' => null]);
            $startDate = Carbon::createFromDate(DB::table('product_movements')->select('created_at')->orderBy('id', 'ASC')->first()->created_at);
            $endDate = Carbon::createFromDate($startDate)->endOfMonth();

            while ($endDate < now()) {
                GeneralConsumptionReport::getConsumptionReport($startDate, $endDate, false);
                $this->info('Fogyásjelentések elkészültek a(z) '.$startDate.' - '.$endDate.' közötti időszakra');
                $startDate = Carbon::createFromDate($startDate)->addMonth()->startOfMonth();
                $endDate = Carbon::createFromDate($startDate)->endOfMonth();
            }

            return CommandAlias::SUCCESS;
        } else {
            $this->startDate = $this->argument('startDate') ?? date('Y-m-d', strtotime('First day of last month')).' 00:00:00';
            $this->endDate = $this->argument('endDate') ?? date('Y-m-d', strtotime('Last day of last month')).' 23:59:59';
            GeneralConsumptionReport::getConsumptionReport($this->startDate, $this->endDate, false);
            $this->info('Fogyásjelentések elkészültek a(z) '.$this->startDate.' - '.$this->endDate.' közötti időszakra');

            return CommandAlias::SUCCESS;
        }
    }
}
