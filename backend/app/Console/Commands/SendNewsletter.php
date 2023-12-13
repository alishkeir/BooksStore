<?php

namespace App\Console\Commands;

use Alomgyar\Products\Product;
use App\Apps\Apps\EmailApp\SendEmail;
use App\Mail\NewsletterEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Körlevél küldése';

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
        $emails = [
            "marton.veszter@weborigo.eu"
        ];

        $text = "Kedves vásárlóink!<br>Köszönjük szépen, hogy vásárlásotokkal támogattátok R. Kelényi Angelika árván maradt kislányát.<br>Az alábbi linkről letölthető a félkész kézirat.<br>%URL%<br><br>Üdvözlettel,<br>Álomgyár";

        foreach ($emails as $email){
            Mail::to($email)
                ->send(new NewsletterEmail(0,"https://alomgyar.hu/RKelenyiAngelika_BecsiKeringo.pdf","Bécsi keringő – félkész letölthető kézirat",$text));
        }

       return 0;
    }
}
