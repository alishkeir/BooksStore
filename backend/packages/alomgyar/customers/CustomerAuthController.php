<?php
/*
Author: Hódi
Date: 2021. 04. 13. 9:01
Project: alomgyar-webshop-be
*/

namespace Alomgyar\Customers;

use Alomgyar\Customers\Actions\AttemptToAuthenticate;
use Alomgyar\Customers\Actions\CreateNewCustomer;
use Alomgyar\Customers\Events\CustomerRegisteredEvent;
use Alomgyar\Customers\Events\CustomerSubscribeToNewsletterEvent;
use Alomgyar\Customers\Events\CustomerVerifiedEvent;
use Alomgyar\Customers\Requests\CustomerLoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Features;
use Laravel\Sanctum\PersonalAccessToken;

class CustomerAuthController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\TokenGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\TokenGuard  $guard
     * @return void
     */
    public function __construct()
    {
//        $this->guard = $guard;
    }

    /**
     * Create a new registered user.
     */
    public function store(Request $request,
                          CreateNewCustomer $creator)
    {
        $customer = $creator->create($request->body);
        if (is_a($customer, MessageBag::class)) {
            return response(['data' => ['errors' => $customer]], 400);
        }
        //event(new CustomerRegisteredEvent($customer));
        event(new CustomerVerifiedEvent($customer));
        if ($customer->marketing_accepted) {
            event(new CustomerSubscribeToNewsletterEvent($customer));
        }
//        $this->guard->login($customer);

        return response(['data' => new CustomerResource($customer)]);
//        return app(RegisterResponse::class);
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param  CustomerLoginRequest  $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->body, [
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Az email mező kötelező',
            'password.required' => 'A jelszó mező kötelező',
        ]);

        if ($validator->fails()) {
            return response(['data' => [
                'errors' => $validator->errors(),
            ]], 400);
        }

        return $this->loginPipeline($request)->then(function ($request) {
            return app(LoginResponse::class);
        });
    }

    /**
     * Get the authentication pipeline instance.
     *
     * @param  CustomerLoginRequest  $request
     * @return \Illuminate\Pipeline\Pipeline
     */
    protected function loginPipeline(Request $request)
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }

    public function checkToken(Request $request)
    {
        if (
            (! is_null($request->bearerToken()) && ! PersonalAccessToken::findToken($request->bearerToken()))
            ||
            (is_null($request->bearerToken()))
        ) {
            return response([
                'data' => [
                    'errors' => [
                        'auth' => 'Hiba lépett fel. Kérlek próbáld meg később!',
                    ],
                ],
            ], 400);
        }

        if (! is_null($request->bearerToken())) {
            $customer = PersonalAccessToken::findToken($request->bearerToken())->tokenable()->first();
            $created_at = PersonalAccessToken::findToken($request->bearerToken())->created_at;

            return [
                'data' => [
                    'token' => $request->bearerToken(),
                    'valid_until' => Customer::tokenValidUntil(),
                    'customer' => new CustomerResource($customer),
                ],
            ];
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        $customer = $personalAccessToken->tokenable()->first();
        if (empty($customer)) {
            return response([
                'errors' => [
                    'auth' => __('auth.failed'),
                ],
            ]);
        }

        $customer->tokens()->delete();

        //$this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response([
            'message' => __('auth.success-logout'),
        ]);
//        return app(LogoutResponse::class);
    }
}
