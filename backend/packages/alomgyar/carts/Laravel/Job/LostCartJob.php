<?php

namespace Alomgyar\Carts\Laravel\Job;

use Alomgyar\Carts\Cart;
use App\Services\Email\LostCartEmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LostCartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function handle(LostCartEmailService $service): void
    {
        $service->sendMail($this->cart);
    }
}
