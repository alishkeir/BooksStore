<?php

namespace App\Console\Commands;

use Alomgyar\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanProductDescription extends Command
{
    protected $take;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:productdescription
                            {take}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually clean all products description html tags';

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
        $from = ['&#60;', '&#62;'];
        $to = ['<', '>'];
        //SELECT * FROM `product` WHERE `description` LIKE '%&#60;%' LIMIT 50
        $products = DB::select(DB::raw(" SELECT product.id FROM `product` WHERE `description` LIKE '%&#60;%' LIMIT 0, ".(intval($this->argument('take') ?? 50))));
        $j = 0;
        foreach ($products as $product) {
            $p = Product::find($product->id);
            $p->description = str_replace($from, $to, $p->description);
            $p->save();
            $j++;
        }
        echo $j;

        return 0;
    }
}
