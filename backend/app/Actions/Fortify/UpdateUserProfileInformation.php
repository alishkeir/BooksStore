<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $customer
     * @return void
     */
    public function update($customer, array $input)
    {
        Validator::make($input, [
            //            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $customer->email &&
            $customer instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($customer, $input);
        } else {
            $customer->forceFill([
                //                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $customer
     * @return void
     */
    protected function updateVerifiedUser($customer, array $input)
    {
        $customer->forceFill([
            //            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $customer->sendEmailVerificationNotification();
    }
}