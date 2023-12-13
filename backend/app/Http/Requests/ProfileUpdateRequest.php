<?php

namespace App\Http\Requests;

use Alomgyar\Customers\Rules\StoreValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    use StoreValidationRules;

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
            'lastname' => ['required', 'string', 'min:2', 'max:60'],
            'firstname' => ['required', 'string', 'min:2', 'max:60'],
            'phone' => ['required', 'regex:/^(?=.*[0-9])[ +0-9]+$/'],
            'store' => $this->storeRules(),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'store' => request('store'),
        ]);
        $this->merge(request()->body);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'email cím',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => 'Az :attribute nem regisztrálható',
        ];
    }
}
