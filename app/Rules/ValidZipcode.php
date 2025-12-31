<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidZipcode implements Rule
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
    public function passes($attribute, $value)
    {
        $APIkey = env('ZIPCODE_API_KEY');
        $json = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $value . '/radians');
        return json_decode($response->getBody())->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
