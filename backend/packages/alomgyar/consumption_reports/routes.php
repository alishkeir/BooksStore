<?php

use Alomgyar\Consumption_reports\ConsumptionReportAuthorComponent;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Alomgyar\Consumption_reports\ConsumptionReportController;
use Alomgyar\Consumption_reports\ConsumptionReportLegalOwnerComponent;
use Alomgyar\Consumption_reports\MerchantImportComponent;
use Alomgyar\Consumption_reports\MerchantReportListComponent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('actual_consumption_report/show', [ConsumptionReportController::class, 'show'])->name('consumption_report.show')->middleware(['can:consumption_reports.show']);
            Route::get('actual_consumption_report/show-author', ConsumptionReportAuthorComponent::class)->name('consumption_report.show-author')->middleware(['can:consumption_reports.show-author']);
            Route::get('actual_consumption_report/show-legal-owner', ConsumptionReportLegalOwnerComponent::class)->name('consumption_report.show-legal')->middleware(['can:consumption_reports.show-legal']);
            Route::get('consumption_report/index', [ConsumptionReportController::class, 'index'])->name('consumption_report.index')->middleware(['can:consumption_reports.index']);

            Route::get('consumption_report/merchant', MerchantReportListComponent::class)->name('consumption_report.merchant')->middleware(['can:consumption_reports.merchant']);
            Route::get('consumption_report/merchant/import', MerchantImportComponent::class)->name('consumption_report.merchant-import')->middleware(['can:consumption_reports.merchant-import']);

            //Route::get('consumption_report/merchant/report', MerchantReportListComponent::class)->name('consumption_report.merchant-report')->middleware(['can:consumption_reports.merchant-report']);
            Route::get('consumption_report/test-generate', [ConsumptionReportController::class, 'generateTest'])->name('consumption_report.test-generate');
            Route::get('consumption_report/regenerate', function () {
                // update product_movements_items set remaining_quantity_from_report = null where created_at between '2021-10-01 00:00:00' and '2021-10-31 23:59:59'
                $startDate = date('Y-m-d', strtotime('First day of last month')).' 00:00:00';
                $endDate = date('Y-m-d', strtotime('Last day of last month')).' 23:59:59';
                DB::table('product_movements_items')->whereBetween('created_at', [$startDate, $endDate])->update(['remaining_quantity_from_report' => null]);
                Artisan::call('report:consumption');

                return redirect()->route('consumption_report.index');
            })->name('consumption_report.regenerate');
            Route::get('consumption_report/author-regenerate', [ConsumptionReportController::class, 'authorRegenerate'])->name('consumption_report.author-regenerate');
            Route::get('consumption_report/legal-regenerate', [ConsumptionReportController::class, 'legalRegenerate'])->name('consumption_report.legal-regenerate');
        });
    });
});
