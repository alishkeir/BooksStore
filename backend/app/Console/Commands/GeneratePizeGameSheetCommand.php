<?php

namespace App\Console\Commands;

use App\Exports\PrizeGameSheetExport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class GeneratePizeGameSheetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:prize-game-sheet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $folder = 'forms';
        $now = Carbon::now()->format('Ymd-His');
        $fileName = $now.'.xlsx';
        $fullPath = $folder.'/'.$fileName;

        //Excel::store(new PrizeGameSheetExport(), $fileName, 'local');
        Excel::store(new PrizeGameSheetExport, $fullPath, 'local');

        Mail::raw('Játék export', function ($message) use ($now, $fullPath) {
            $message->to('zoltan.bencsik@weborigo.eu')
              ->subject('Játék export '.$now)
              ->attach(storage_path('app/'.$fullPath));
        });
    }
}
