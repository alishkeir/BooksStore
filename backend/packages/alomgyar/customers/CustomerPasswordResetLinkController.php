<?php

namespace Alomgyar\Customers;

use Alomgyar\Customers\Rules\StoreValidationRules;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Fortify;

class CustomerPasswordResetLinkController extends Controller
{
    use StoreValidationRules;

    /**
     * Show the reset password link request view.
     */
    public function create(Request $request): RequestPasswordResetLinkViewResponse
    {
        return app(RequestPasswordResetLinkViewResponse::class);
    }

    /**
     * Send a reset link to the given user.
     */
    public function store(Request $request)
    {
        $input = $request->body;
        $input['store'] = $request->store;
        $validator = $this->validate($input);
        if ($validator->fails()) {
            return [
                'data' => [
                    'errors' => $validator->errors(),
                ],
            ];
        }
        // We will send the password reset link to this customer. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = $this->broker()->sendResetLink(
//            $request->only(Fortify::email(), 'store')
            array_merge($request->body, ['store' => $request->store])
        );

        return response([
            'data' => $status == Password::RESET_LINK_SENT
                ? [
                    'message' => 'Jelszó emlékeztető email elküldve az email címedre',
                ]
                : [
                    'errors' => [
                        'message' => ['Hiba lépett fel, kérlek próbáld meg újra később'],
                    ]],
        ], $status == Password::RESET_LINK_SENT ? 200 : 400);

//        return $status == Password::RESET_LINK_SENT
//            ? app(SuccessfulPasswordResetLinkRequestResponse::class, ['status' => $status])
//            : app(FailedPasswordResetLinkRequestResponse::class, ['status' => $status]);
    }

    private function validate($input)
    {
        return Validator::make($input, [
            'email' => 'required|email',
            'store' => $this->storeRules(),
        ], [
            'email.required' => __('The email field is required.'),
        ])->after(function ($validator) use ($input) {
            if (! isset($input['email'])) {
                return;
            }
            $customer = Customer::whereEmail($input['email'])
                                ->whereStore($input['store'])
                                ->first();

            if (empty($customer)) {
                $validator->errors()->add('auth', __('auth.failed'));
            }
        });
    }

    /**
     * Get the broker to be used during password reset.
     */
    protected function broker(): PasswordBroker
    {
//        return Password::broker(config('fortify.passwords'));
        return Password::broker(config('fortify.customer_passwords'));
    }
}
