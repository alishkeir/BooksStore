<?php

namespace Alomgyar\Consumption_reports\Commands;

use Alomgyar\Consumption_reports\Reports\LegalOwnerConsumptionReport;
use Illuminate\Console\Command;

class LegalOwnerConsumptionReportCommand extends Command
{
    protected bool $reportOnly = false;

    protected $startDate;

    protected $endDate;

    protected $signature = 'report:legal-consumption {startDate?} {endDate?}';

    protected $description = 'Create a monthly report on legal owners` consumption of products';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->startDate = $this->argument('startDate') ?? date('Y-m-d', strtotime('First day of last month')).' 00:00:00';
        $this->endDate = $this->argument('endDate') ?? date('Y-m-d', strtotime('Last day of last month')).' 23:59:59';
        LegalOwnerConsumptionReport::getConsumptionReport($this->startDate, $this->endDate, $this->reportOnly);

        $this->info('Jogtulajdonos fogyásjelentések elkészültek');
    }
}
