<?php

namespace Alomgyar\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerVerifyEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        dd('$vars');
        if (! hash_equals((string) $this->route('hash'), sha1($this->customer()->find($this->route('id'))->getEmailForVerification()))) {
            return false;
        }

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
            //
        ];
    }
}
