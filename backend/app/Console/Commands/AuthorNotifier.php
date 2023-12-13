<?php

namespace App\Console\Commands;

use Alomgyar\Customers\CustomerAuthor;
use Alomgyar\Products\Product;
use Illuminate\Console\Command;

class AuthorNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authors:notifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Új könyv bekerülése esetén kiértesíti a szerzőre feliratkozott felhasználókat';

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
        $authors = [];

        /* kikeressük az újonnan felvett könyveket */

        $products = Product::where([['created_at', '>', date('Y-m-d H:i:s', strtotime('now - 1 day'))], ['status', Product::STATUS_ACTIVE]])->get();

        foreach ($products as $product) {
            foreach ($product->author as $author) {
                /* termékenként lekérdezzük a szerzőket, és begyűjtjük a könyvekkel együtt */
                if (! array_key_exists($author->id, $authors)) {
                    $authors[$author->id] = [
                        'author' => $author,
                        'products' => [
                            $product,
                        ],
                    ];
                } else {
                    /* ha már létezik a szerző a tömbben, csak hozzáadjuk a könyvet */
                    $authors[$author->id]['products'][] = $product;
                }
            }
        }

        /* emailek küldése */
        $mailToCustomers = [];
        $customers = CustomerAuthor::with('customer')->get();

        foreach ($customers as $customer) {
            if (array_key_exists($customer->author_id, $authors)) { // ha a usernek van feliratkozott szerzője a tömbben
                if (! array_key_exists($customer->customer->email, $mailToCustomers)) {
                    $mailToCustomers[$customer->customer->email] = [
                        'authors' => [$authors[$customer->author_id]]];
                } else {
                    $mailToCustomers[$customer->customer->email]['authors'][] = $authors[$customer->author_id];
                }
            }
        }
        //var_dump($mailToCustomers);
        /* emailküldés */
        foreach ($mailToCustomers as $key => $mail) {
            echo $key.' - ';
            foreach ($mail['authors'] as $author) {
                echo $author['author']->title.' -- ';
            }
            echo PHP_EOL;
        }
    }
}
