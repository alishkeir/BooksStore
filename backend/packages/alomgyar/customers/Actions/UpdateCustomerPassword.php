<?php

namespace Alomgyar\Customers\Actions;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateCustomerPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $customer
     * @return void
     */
    public function update($customer, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validateWithBag('updatePassword');

        $customer->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
