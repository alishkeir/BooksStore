<?php

namespace App\Console\Commands;

use Alomgyar\Products\Product;
use App\Jobs\AuthorNewBookJob;
use Illuminate\Console\Command;

class SendProductMailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:productmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Az szerzőértesítő leveleket küldi ki a megfelelő termékekhez tartozó mailekre';

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
        $count = 0;

        $products = Product::query()
            ->authorMailNotSentYet()
            ->active()
            ->get();

        foreach ($products as $product) {
            //AuthorNewBookJob::dispatch($product)
            AuthorNewBookJob::dispatch($product)->delay(now()->addHours(4));

            $product->author_mail_sent = Product::AUTHOR_EMAIL_SENT;
            $product->save();

            $count++;
        }

        // LATER WE WILL GET BACK

        echo date('m.d H:i:s').' '.$count.' products mails sent...'.PHP_EOL;

        // //-----
        // $products = DB::select(DB::raw("
        // SELECT id FROM product
        // WHERE author_mail_sent = 0
        // AND status = 1
        // AND deleted_at IS NULL
        // ")); //AND created_at > '".Carbon::yesterday()."'

        // foreach ($products as $product) {
        //     //deleted event(new \Alomgyar\Products\Events\ProductOrderableEvent(\Alomgyar\Products\Product::find($product->id)));

        //     event(new \Alomgyar\Products\Events\AuthorNewBookEvent(\Alomgyar\Products\Product::find($product->id)));
        //     $count++;
        // }
        // unset($products);

        // DB::statement("UPDATE product SET author_mail_sent = 1
        // WHERE author_mail_sent = 0
        // AND status = 1
        // AND deleted_at IS NULL");
    }
}
