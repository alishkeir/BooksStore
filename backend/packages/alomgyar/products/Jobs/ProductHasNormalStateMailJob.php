<?php

namespace Alomgyar\Products\Jobs;

use Alomgyar\Products\Mails\ProductHasNormalStateMail;
use App\WishItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ProductHasNormalStateMailJob implements ShouldQueue
{
    private WishItem $wishItem;

    public function __construct(WishItem $wishItem)
    {
        $this->wishItem = $wishItem;
    }

    public function handle()
    {
        if (! $this->wishItem->customer || ! $this->wishItem->customer->email) {
            return;
        }

        Mail::to(trim($this->wishItem->customer->email))->send(new ProductHasNormalStateMail($this->wishItem->product, $this->wishItem->customer));

        $this->wishItem->notified_at = Carbon::now();
        $this->wishItem->save();
    }
}
