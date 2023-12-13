<?php

namespace App\Console\Commands;

use Alomgyar\PickUpPoints\Providers\DPD;
use Alomgyar\PickUpPoints\Providers\Easybox;
use Alomgyar\PickUpPoints\Providers\FoxPost;
use Alomgyar\PickUpPoints\Providers\GLS;
use Alomgyar\PickUpPoints\Providers\Packeta;
use Alomgyar\PickUpPoints\Providers\PickPackPoint;
use Alomgyar\PickUpPoints\Providers\Posta;
use Illuminate\Console\Command;

class BoxScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:box
                            {boxProvider : DPD, FoxPost, GLS, PickPackPoint, Posta, Easybox, Packeta}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Csomagpontok (boxok, pickpackpointok) frissítése (DPD, FoxPost, GLS, PickPackPoint, Posta, Easybox, Packeta)';

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
        switch ($this->argument('boxProvider')) {
            case 'GLS':
                (new GLS)->collect();
                break;
            case 'Posta':
                (new Posta)->collect();
                break;
            case 'FoxPost':
                (new FoxPost)->collect();
                break;
            case 'DPD':
                (new DPD)->collect();
                break;
            case 'PickPackPoint':
                (new PickPackPoint)->collect();
                break;
            case 'Easybox':
                (new Easybox)->collect();
                break;
            case 'Packeta':
                (new Packeta)->collect();
                break;

            default:
                echo 'something missing'.PHP_EOL;
                break;
        }
    }
}
