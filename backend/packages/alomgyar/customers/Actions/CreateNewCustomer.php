<?php
/*
Author: Hódi
Date: 2021. 04. 13. 9:12
Project: alomgyar-webshop-be
*/

namespace Alomgyar\Customers\Actions;

use Alomgyar\Customers\Customer;
use Alomgyar\Customers\Rules\PasswordValidationRules;
use Alomgyar\Customers\Rules\StoreValidationRules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewCustomer implements CreatesNewUsers
{
    use PasswordValidationRules, StoreValidationRules;

    protected $messages = [
        'email.required' => 'Email mező kitöltése kötelező',
        'email.unique' => 'A megadott adatok nem megfelelőek',
        'email.confirmed' => 'Az email mezők nem egyeznek',
        'email.email' => 'A megadott email cím érvénytelen',
        'email.max' => 'Nem lehet hosszabb 255 karakternél',
        'password' => 'jelszó',
        'password.required' => 'Jelszó mező kitöltése kötelező',
        'password.confirmed' => 'Jelszó mezők nem egyeznek',
        'password.confirmed' => 'Jelszó mezők nem egyeznek',
        'tac_accepted.required' => 'A feltételek elfogadása kötelező',
        'tac_accepted.accepted' => 'A feltételek elfogadása kötelező',
    ];

    /**
     * Validate and create a newly registered customer.
     *
     * @return Customer
     */
    public function create(array $input)
    {
        $input['store'] = request('store');

        $validator = Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'confirmed',
                Rule::unique(Customer::class, 'email')->where(function ($query) {
                    return $query->whereStore(request('store'));
                }),
            ],
            'password' => $this->passwordRules(),
            'store' => $this->storeRules(),
            'tac_accepted' => ['required', 'accepted'],
        ], $this->messages);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return Customer::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'store' => $input['store'],
            'status' => 1, // CR: get rid of email verification
            'email_verified_at' => date('Y-m-d H:i:s'), // CR: get rid of email verification
            'marketing_accepted' => in_array($input['marketing_accepted'], ['yes', 'on', true, 1, 'true', '1']) ? 1 : 0,
            'tac_accepted' => in_array($input['tac_accepted'], ['yes', 'on', true, 1, 'true', '1']) ? 1 : 0,
        ]);
    }
}
