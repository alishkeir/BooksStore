<?php

namespace Alomgyar\Customers\Actions;

use Alomgyar\Carts\Cart;
use Alomgyar\Customers\Customer;
use Alomgyar\Products\ProductPrice;
use App\Http\Resources\CustomerResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\LoginRateLimiter;
use Laravel\Sanctum\PersonalAccessToken;

class AttemptToAuthenticate
{
    use ErrorMessages;

    protected $guard;

    protected $limiter;

    private $customer;

    private $itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

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

    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $this->customer = Customer::verified()->whereEmail($request->body['email'])->whereStore($request->store)->first();

        if (! $this->customer || ! Hash::check($request->body['password'], $this->customer?->password)) {
            // még egy ellenőrzést hajtsunk végre régi userekkel
            if ($this->oldPasswordFailed($request->body['password'])) {
                $this->fireFailedEvent($request);

                return $this->throwFailedAuthenticationException($request);
            }
        }

        if (! is_null($request->bearerToken()) && ! PersonalAccessToken::findToken($request->bearerToken())) {
            return $this->authFailedMessage();
        }

        if (isset($request->body['remember_me']) && $request->body['remember_me']) {
            $this->customer->setRememberToken(Str::random(60));
        } else {
            $this->customer->remember_token = 0;
        }

        $this->customer->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp(),
            'last_login_device' => '',
        ]);

        $this->mergeCart();

        return response([
            'data' => [
                'token' => $this->customer->createToken('auth')->plainTextToken,
                'valid_until' => Customer::tokenValidUntil(),
                'customer' => new CustomerResource($this->customer),
            ],
        ]);
    }

    /**
     * Throw a failed authentication validation exception.
     *
     * @param  Request  $request
     */
    protected function throwFailedAuthenticationException($request)
    {
        $this->limiter->increment($request);

        return response([
            'data' => [
                'errors' => [
                    'auth' => [trans('auth.failed')],
                ],
            ],
        ], 400);
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  Request  $request
     * @return void
     */
    protected function fireFailedEvent($request)
    {
        event(new Failed(config('fortify.guard'), null, [
            Fortify::username() => $request->body['email'],
            'password' => $request->body['password'],
        ]));
    }

    private function mergeCart()
    {
        if (isset(request()->body['guest_token']) && $guestCart = Cart::where('guest_token',
            request()->body['guest_token'])->first()) {
            if ($customerCart = $this->customer->cart()->first()) {
                $formerCartItems = $customerCart->items
                    ->transform(function ($item) {
                        return $item->only(['product_id', 'quantity', 'is_cart_price']);
                    })
                    ->keyBy('product_id')
                    ->keys();
                $guestCart->items()->whereNotIn('product_id',
                    $formerCartItems)->update(['cart_id' => $customerCart->id]);
                $guestCart->items()->whereIn('product_id', $formerCartItems)->forceDelete();
                $guestCart->forceDelete();
                $customerCart->refresh();
                $customerCart->total_amount = $this->calculateTotalAmount($customerCart);
                $customerCart->total_amount_full_price = $this->calculateTotalAmountFullPrice($customerCart);
                $customerCart->save();
            } else {
                $guestCart->update(['customer_id' => $this->customer->id]);
            }
        }
    }

    private function calculateTotalAmount($customerCart)
    {
        return ProductPrice::select('product_id', 'price_sale', 'price_cart')
                           ->whereStore(request('store'))
                           ->whereIn('product_id', $customerCart->items->pluck('product_id'))
                           ->get()
                           ->transform(function ($item) use ($customerCart) {
                               $cartItem = $customerCart->items->where('product_id', $item->product_id)->first();
                               $quantity = $cartItem?->quantity ?? 1;
                               $price = $cartItem?->is_cart_price
                                   ? $item->price_cart * $quantity
                                   : $item->price_sale * $quantity;

                               return $price;
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

    private function oldPasswordFailed(mixed $password): bool
    {
        if (strlen($password) > 4096) {
            return true;
        }
        $hash = $this->cryptPrivate($password, $this->customer?->password);

        if ($hash[0] == '*' || $hash == 0) {
            $hash = crypt($password, $this->customer?->password);
        }

        return $hash !== $this->customer?->password;
    }

    private function cryptPrivate(mixed $password, $storedHash): string
    {
        $output = '0';

        if (substr($storedHash, 0, 2) == $output) {
            $output = '1';
        }

        $id = substr($storedHash, 0, 3);
        // We use "$P$", phpBB3 uses "$H$" for the same thing
        if ($id != '$P$' && $id != '$H$') {
            return $output;
        }
        $count_log2 = strpos($this->itoa64, $storedHash[3]);
        if ($count_log2 < 7 || $count_log2 > 30) {
            return $output;
        }
        $count = 1 << $count_log2;
        $salt = substr($storedHash, 4, 8);
        if (strlen($salt) != 8) {
            return $output;
        }
        // We're kind of forced to use MD5 here since it's the only
        // cryptographic primitive available in all versions of PHP
        // currently in use.  To implement our own low-level crypto
        // in PHP would result in much worse performance and
        // consequently in lower iteration counts and hashes that are
        // quicker to crack (by non-PHP code).
        if (PHP_VERSION >= '5') {
            $hash = md5($salt.$password, true);
            do {
                $hash = md5($hash.$password, true);
            } while (--$count);
        } else {
            $hash = pack('H', md5($salt.$password));
            do {
                $hash = pack('H', md5($hash.$password));
            } while (--$count);
        }
        $output = substr($storedHash, 0, 12);
        $output .= $this->encode64($hash, 16);

        return $output;
    }

    private function encode64(string $hash, int $int): string
    {
        $output = '';
        $i = 0;
        do {
            $value = ord($hash[$i++]);
            $output .= $this->itoa64[$value & 0x3F];
            if ($i < $int) {
                $value |= ord($hash[$i]) << 8;
            }
            $output .= $this->itoa64[($value >> 6) & 0x3F];
            if ($i++ >= $int) {
                break;
            }
            if ($i < $int) {
                $value |= ord($hash[$i]) << 16;
            }
            $output .= $this->itoa64[($value >> 12) & 0x3F];
            if ($i++ >= $int) {
                break;
            }
            $output .= $this->itoa64[($value >> 18) & 0x3F];
        } while ($i < $int);

        return $output;
    }
}
