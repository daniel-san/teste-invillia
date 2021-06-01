<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;

class ValidXml implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $xmlString = $value->get();

        try {
            $xml = simplexml_load_string($xmlString);
        } catch (Exception $e) {
            return false;
        }

        return empty($xml) ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute xml file is malformed.';
    }
}
