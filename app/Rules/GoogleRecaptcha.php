<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class GoogleRecaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $capcha_response)
    {
        $api_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('CAPTCHA_SERVER_KEY'),
            'response' => $capcha_response,
        ])->json();

        return $api_response['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ошибка проверки капчи';
    }
}
