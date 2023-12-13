<?php

namespace Alomgyar\Customers\Rules;

use Illuminate\Validation\Rule;

trait StoreValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function storeRules()
    {
        return ['required', Rule::in([0, 1, 2])];
    }
}
