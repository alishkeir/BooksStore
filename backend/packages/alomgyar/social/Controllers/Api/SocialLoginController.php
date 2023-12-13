<?php

namespace Alomgyar\Social\Controllers\Api;

use Alomgyar\Customers\Actions\AttemptToSocialAuthenticate;
use Alomgyar\Customers\Customer;
use App\Http\Traits\ErrorMessages;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Features;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController
{
    use ErrorMessages;

    public function __invoke(Request $request)
    {
        $request->validate([
            'body.token' => 'required',
            'body.provider' => 'required',
        ]);

        $token = $request->input('body.token');

        $provider = $request->input('body.provider');

        $socialUser = Socialite::driver($provider)->userFromToken($token);

        $name = explode(' ', $socialUser->getName());

        $firstName = $name[0];

        unset($name[0]);

        $lastName = implode(' ', $name);

        try {
            $customer = Customer::firstOrCreate([
                'email' => $socialUser->getEmail(),
                'provider_id' => $socialUser->getId(),
                'store' => request('store'),
            ], [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => md5(Carbon::now()),
                'email_verified_at' => Carbon::now(),
                'status' => Customer::STATUS_ACTIVE,
            ]);
        } catch (QueryException $e) {
            Log::error($e->getMessage());

            return response([
                'data' => [
                    'errors' => [
                        $socialUser->getEmail()
                            ? 'Hiba történt! Próbáld meg a jelszóemlékeztetőt.'
                            : 'Email cím megadása kötelező',
                    ],
                ],
            ], 400);
        }

        $request->request->add(['customer' => $customer]);

        return $this->loginPipeline($request)->then(function ($request) {
            return app(LoginResponse::class);
        });
    }

    private function loginPipeline($request)
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            AttemptToSocialAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }
}
