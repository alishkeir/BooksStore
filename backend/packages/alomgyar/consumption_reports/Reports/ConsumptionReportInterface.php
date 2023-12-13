<?php
/*
Author: Hódi
Date: 2022. 01. 21. 16:15
Project: alomgyar-webshop-be
*/

namespace Alomgyar\Consumption_reports\Reports;

interface ConsumptionReportInterface
{
    public static function getConsumptionReport($startDate = null, $endDate = null, bool $reportOnly = false);

    public static function getConsumptions();

    public static function consumptionData($consumptions);

    public static function getSums($grouped);

    public static function createExcelFiles();

    public static function saveReport(array $files);
}
