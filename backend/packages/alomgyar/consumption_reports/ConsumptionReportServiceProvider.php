<?php

namespace Alomgyar\Consumption_reports;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ConsumptionReportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'consumption_reports');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'consumption_reports');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/consumption_reports'),
        ]);
        Livewire::component('consumption_reports::listcomponent', ListComponent::class);
        Livewire::component('consumption_reports::consumption-report', ConsumptionReportComponent::class);
        Livewire::component('consumption_reports::consumption-report-author', ConsumptionReportAuthorComponent::class);
        Livewire::component('consumption_reports::consumption-report-legal', ConsumptionReportLegalOwnerComponent::class);
        Livewire::component('consumption_reports::merchant-import', MerchantImportComponent::class);
        Livewire::component('consumption_reports::merchant', MerchantReportListComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Consumption_reports\ConsumptionReportController');
    }
}
