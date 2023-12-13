<?php

namespace App\Jobs;

use Alomgyar\Products\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Book24StockUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;

    public $inStock;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product, $inStock = 1)
    {
        $this->product = $product;
        $this->inStock = $inStock;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->product->book24_stock = $this->inStock;
        $this->product->save();
    }
}
