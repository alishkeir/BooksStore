<?php

namespace Alomgyar\Customers\Actions;

use Alomgyar\Carts\Cart;
use Alomgyar\Customers\Customer;
use Alomgyar\Products\ProductPrice;
use App\Http\Resources\CustomerResource;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Laravel\Fortify\LoginRateLimiter;

class AttemptToSocialAuthenticate
{
    protected $guard;

    protected $limiter;

    /** @var Customer */
    protected $customer;

    /**
     * Create a new controller instance.
     *
     *
     * @return void
     */
    public function __construct(StatefulGuard $guard, LoginRateLimiter $limiter)
    {
        $this->guard = $guard;
        $this->limiter = $limiter;
    }

    public function handle(Request $request, $next)
    {
        $this->customer = $request->get('customer');

        if (! $this->customer) {
            return $this->socialAuthFail();
        }

        $this->mergeCart();

        $this->customer->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp(),
            'last_login_device' => '',
        ]);

        return response([
            'data' => [
                'token' => $this->customer->createToken('auth')->plainTextToken,
                'valid_until' => Customer::tokenValidUntil(),
                'customer' => new CustomerResource($this->customer),
            ],
        ]);
    }

    private function socialAuthFail(): Response
    {
        return response([
            'data' => [
                'errors' => [__('social-auth.failed')],
            ],
        ], 403);
    }

    private function mergeCart()
    {
        if (isset(request()->body['guest_token']) && $guestCart = Cart::where('guest_token', request()->body['guest_token'])->first()) {
            if ($customerCart = $this->customer->cart()->first()) {
                $formerCartItems = $customerCart->items
                    ->transform(function ($item) {
                        return $item->only(['product_id', 'quantity', 'is_cart_price']);
                    })
                    ->keyBy('product_id')
                    ->keys();
                $guestCart->items()->whereNotIn('product_id', $formerCartItems)->update(['cart_id' => $customerCart->id]);
                $guestCart->items()->whereIn('product_id', $formerCartItems)->forceDelete();
                $guestCart->forceDelete();
                $customerCart->refresh();
                $customerCart->total_amount = $this->calculateTotalRealAmount($customerCart);
                $customerCart->total_amount_full_price = $this->calculateTotalAmountFullPrice($customerCart);
                $customerCart->save();
            } else {
                $guestCart->update(['customer_id' => $this->customer->id]);
            }
        }
    }

    private function calculateTotalRealAmount($customerCart)
    {
        return ProductPrice::select('product_id', 'price_sale')
            ->whereStore(request('store'))
            ->whereIn('product_id', $customerCart->items->pluck('product_id'))
            ->get()
            ->transform(function ($item) use ($customerCart) {
                return $item->price_sale * ($customerCart->items->where('product_id',
                    $item->product_id)->first()->quantity ?? 1);
            })
            ->sum();
    }

    private function calculateTotalAmountFullPrice($customerCart)
    {
        return ProductPrice::select('product_id', 'price_list')
            ->whereStore(request('store'))
            ->whereIn('product_id', $customerCart->items->pluck('product_id'))
            ->get()
            ->transform(function ($item) use ($customerCart) {
                return $item->price_list * ($customerCart->items->where('product_id',
                    $item->product_id)->first()->quantity ?? 1);
            })
            ->sum();
    }
}
