<?php

namespace App\Console;

use Alomgyar\Carts\Laravel\Commands\LostCartCommand;
use Alomgyar\Consumption_reports\Commands\AuthorConsumptionReportCommand;
use Alomgyar\Consumption_reports\Commands\ConsumptionReportCommand;
use Alomgyar\Consumption_reports\Commands\LegalOwnerConsumptionReportCommand;
use Alomgyar\PickUpPoints\Command\PickUpPointCommand;
use Alomgyar\RankedProducts\Commands\RankedProductsCommand;
use Alomgyar\Recommenders\Laravel\Commands\SendRecommendersCommand;
use DateTimeZone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SendRecommendersCommand::class,
        LostCartCommand::class,
        ConsumptionReportCommand::class,
        AuthorConsumptionReportCommand::class,
        LegalOwnerConsumptionReportCommand::class,
        RankedProductsCommand::class,
        PickUpPointCommand::class,
        // register commands here
    ];

    /**
     * Define the application's command schedule.
     *
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->daily();
        if (config('telescope.enabled')) {
            $schedule->command('telescope:prune')->daily();
        }

        $schedule->command('tokens:prune')->weekly();

        //Recalculate all prices for products (list, sale, newdiscount, promotions)
        $schedule->command('calculate:prices')->everyTenMinutes()->withoutOverlapping(5);

        //Run 'Fogyásjelentés' monthly
        $schedule->command('report:consumption')->monthlyOn(1, '01:40');
        $schedule->command('report:author-consumption')->monthlyOn(1, '02:00');
        $schedule->command('report:legal-consumption')->monthlyOn(1, '02:20');

        $schedule->command('report:consumption')->monthlyOn(15, '01:40');
        $schedule->command('report:author-consumption')->monthlyOn(15, '02:00');
        $schedule->command('report:legal-consumption')->monthlyOn(15, '02:20');

        $schedule->command('report:consumption')->monthlyOn(28, '01:40');
        $schedule->command('report:author-consumption')->monthlyOn(28, '02:00');
        $schedule->command('report:legal-consumption')->monthlyOn(28, '02:20');

        // Calculate orders count and preorders count per store
        $schedule->command('calculate:orders')->dailyAt('22:01')->withoutOverlapping(10);
        $schedule->command('calculate:preorders')->dailyAt('23:01')->withoutOverlapping(10);

        // Run ranked products
        $schedule->command('ranked:determine')->dailyAt('00:01')->withoutOverlapping(10);

        //        $schedule->command('queue:work --max-time=90 --tries=3')->everyThreeMinutes();
        $schedule->command('queue:retry all')->everyTenMinutes()->withoutOverlapping();

        //Dibook sync daily
        $schedule->command('sync:dibook')->dailyAt('00:10');

        $schedule->command('xml:arukereso')->dailyAt('00:20');
        $schedule->command('xml:google')->dailyAt('00:30');

        // PickUpPoints update
        //$schedule->command('pick_up_point:update')->everyTenHours();

        $schedule->command('sync:box DPD')->everySixHours()->withoutOverlapping(30);
        $schedule->command('sync:box Posta')->everySixHours()->withoutOverlapping(30);
        $schedule->command('sync:box PickPackPoint')->everySixHours()->withoutOverlapping(30);
        $schedule->command('sync:box FoxPost')->everySixHours()->withoutOverlapping(30);
        $schedule->command('sync:box Easybox')->everySixHours()->withoutOverlapping(30);

        $schedule->command('activity-log:cleanup')->dailyAt('04:00')->withoutOverlapping(10);

        //--------------------------------------
        // NEED TO FIX THIS ALSO
        //set Preordable based on book24 stock and pam stock
        //--------------------------------------
        //$schedule->command('sync:book24 setPreorderable')->everySixHours()->withoutOverlapping();

        //--------------------------------------
        //--------------------------------------
        // BOOK24 sync, create new books
        $schedule->command('sync:book24 justDownload')->dailyAt('02:00')->withoutOverlapping(30);
        $schedule->command('sync:book24 saveNewProducts')->dailyAt('02:15')->withoutOverlapping(30);
        //--------------------------------------
        // TEMP REMOVE UNTIL DISCUSS ABOUT PRICE AUTOMATIONS
        //$schedule->command('sync:book24 updateProductPrices')->everyThreeHours()->withoutOverlapping(30);
        //--------------------------------------

        // Update stock info
        // $schedule->command('sync:book24 justDownloadStockInfo')->dailyAt('02:30')->withoutOverlapping(30);
        // $schedule->command('sync:book24 updateProductStock')->dailyAt('02:45')->withoutOverlapping(30);

        //--------------------------------------
        //--------------------------------------

        //Low stock emails
        $schedule->command('send:lowstockmails')->hourly()->withoutOverlapping(10);
        //$schedule->command('send:lowstockmails')->everySixHours()->withoutOverlapping();

        // EMAILS //
        // Run recommender
        $schedule->command('recommenders:send')->everyTenMinutes()->withoutOverlapping(5);
        // Lost cart command
        $schedule->command('cart:send-lost')->dailyAt('08:00')->withoutOverlapping(10);
        // Send Author mails
        $schedule->command('send:productmails')->hourly()->withoutOverlapping(10);

        // $schedule->command('generate:prize-game-sheet')->dailyAt('07:58');
        // $schedule->command('generate:prize-game-sheet')->dailyAt('16:30');

        // END EMAILS //
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     */
    protected function scheduleTimezone(): DateTimeZone|string|null
    {
        return 'Europe/Budapest';
    }
}
