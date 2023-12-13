<?php

namespace Alomgyar\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Alomgyar\Customers\Rules\PasswordValidationRules;
class CustomerChangePasswordRequest extends FormRequest
{
    use PasswordValidationRules;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body.email' => 'required|string',
            'body.password' => $this->passwordRules()
        ];
    }
}
