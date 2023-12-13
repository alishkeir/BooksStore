<?php

namespace Alomgyar\Recommenders\Laravel\Commands;

use Alomgyar\Recommenders\Laravel\Jobs\RecommenderSendJob;
use Alomgyar\Recommenders\Recommender;
use Alomgyar\Recommenders\Repository\RecommenderRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendRecommendersCommand extends Command
{
    protected $signature = 'recommenders:send';

    protected $description = 'Ajánlások kiküldése, a megadott időben';

    public function handle(RecommenderRepository $repository)
    {
        $recommeenders = Recommender::whereNull('released_at')->where('release_date', '<=', Carbon::now())->get();

        if (empty($recommeenders)) {
            return;
        }
        $sent = $sent2 = 0;
        foreach ($recommeenders as $recommeender) {
            $customers = $repository->getCustomersByProductId($recommeender->original_product_id);

            foreach ($customers as $customer) {
                try {
                    dispatch(new RecommenderSendJob($customer, $recommeender));
                    $sent++;
                } catch(\Exception $e) {
                    logger($e->getMessage());
                }
            }

            $customers_archive = $repository->getCustomersByProductIdArchive($recommeender->original_product_id);

            foreach ($customers_archive as $customer) {
                try {
                    dispatch(new RecommenderSendJob($customer, $recommeender));
                    $sent2++;
                } catch(\Exception $e) {
                    logger($e->getMessage());
                }
            }

            Log::info('Recommenders mail sent. ('.implode(',', $customers->pluck('email')->toArray()).')');

            $recommeender->released_at = Carbon::now();
            $recommeender->save();
        }
        $this->info('A kiküldés sikeres volt ('.$sent.' új és '.$sent2.' archív rendelés alapján)');
    }
}
