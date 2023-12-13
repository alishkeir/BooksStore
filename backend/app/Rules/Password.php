<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Password implements Rule
{
    /**
     * The minimum length of the password.
     */
    protected int $length = 8;

    /**
     * Indicates if the password must contain one uppercase character.
     */
    protected bool $requireUppercase = false;

    /**
     * Indicates if the password must contain one numeric digit.
     */
    protected bool $requireNumeric = false;

    /**
     * Indicates if the password must contain one special character.
     */
    protected bool $requireSpecialCharacter = false;

    /**
     * The message that should be used when validation fails.
     *
     * @var string
     */
    protected $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = is_scalar($value) ? (string) $value : '';

        if ($this->requireUppercase && Str::lower($value) === $value) {
            return false;
        }

        if ($this->requireNumeric && ! preg_match('/[0-9]/', $value)) {
            return false;
        }

        if ($this->requireSpecialCharacter && ! preg_match('/[\W_]/', $value)) {
            return false;
        }

        return Str::length($value) >= $this->length;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->message) {
            return $this->message;
        }

        switch (true) {
            case $this->requireUppercase
            && ! $this->requireNumeric
            && ! $this->requireSpecialCharacter:
                return __('A :attribute legalább :length karakter legyen és tartalmazzon legalább 1 nagybetűt.', [
                    'length' => $this->length,
                ]);

            case $this->requireNumeric
            && ! $this->requireUppercase
            && ! $this->requireSpecialCharacter:
                return __('A :attribute legalább :length karakter legyen és tartalmazzon legalább 1 számot.', [
                    'length' => $this->length,
                ]);

            case $this->requireSpecialCharacter
            && ! $this->requireUppercase
            && ! $this->requireNumeric:
                return __('A :attribute legalább :length karakter legyen és tartalmazzon legalább 1 speciális karaktert.', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
            && $this->requireNumeric
            && ! $this->requireSpecialCharacter:
                return __('A :attribute legalább :length karakter legyen és tartalmazzon legalább 1 nagybetűt és 1 számot.', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
            && $this->requireSpecialCharacter
            && ! $this->requireNumeric:
                return __('A :attribute legalább :length karakter legyen és tartalmazzon legalább 1 nagybetűt és 1 speciális karaktert.', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
            && $this->requireNumeric
            && $this->requireSpecialCharacter:
                return __('A :attribute legalább :length karakter legyen és tartalmazzon legalább 1 nagybetűt, 1 számot és 1 speciális karaktert.', [
                    'length' => $this->length,
                ]);

            case $this->requireNumeric
            && $this->requireSpecialCharacter
            && ! $this->requireUppercase:
                return __('A :attribute legalább :length karakter legyen és tartalmazzon legalább 1 számot és 1 speciális karaktert.', [
                    'length' => $this->length,
                ]);

            default:
                return __('A :attribute legalább :length karakter legyen.', [
                    'length' => $this->length,
                ]);
        }
    }

    /**
     * Set the minimum length of the password.
     *
     *
     * @return \Laravel\Fortify\Rules\Password
     */
    public function length(int $length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Indicate that at least one uppercase character is required.
     *
     * @return $this
     */
    public function requireUppercase()
    {
        $this->requireUppercase = true;

        return $this;
    }

    /**
     * Indicate that at least one numeric digit is required.
     *
     * @return $this
     */
    public function requireNumeric()
    {
        $this->requireNumeric = true;

        return $this;
    }

    /**
     * Indicate that at least one special character is required.
     *
     * @return $this
     */
    public function requireSpecialCharacter()
    {
        $this->requireSpecialCharacter = true;

        return $this;
    }

    /**
     * Set the message that should be used when the rule fails.
     *
     * @return $this
     */
    public function withMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'password' => 'jelszó',
        ];
    }
}
