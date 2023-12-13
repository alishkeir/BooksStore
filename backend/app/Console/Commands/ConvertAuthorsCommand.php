<?php

namespace App\Console\Commands;

use Alomgyar\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConvertAuthorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authors:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Author relation -> authors field';

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
        Product::select('id')->orderBy('id')->chunk(100, function ($products) {
            foreach ($products as $product) {
                DB::table('product')->where('id', $product->id)->update(['authors' => implode(', ', $product->author->pluck('title')->toArray())]);
                $this->info($product->id.' authors: '.implode(', ', $product->author->pluck('title')->toArray()));
            }
        });
    }
}
