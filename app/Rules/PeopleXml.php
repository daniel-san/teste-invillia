<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PeopleXml implements Rule
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
        $peopleXml = $value->get();

        try {
            $this->validateXml($peopleXml);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    protected function validateXml($xml)
    {
        $parsed = json_encode(simplexml_load_string($xml));
        $xmlArray = json_decode($parsed, true);

        $xmlArray = collect($xmlArray)->first();

        foreach($xmlArray as $key => $value) {
            $phones = data_get($value, 'phones');

            if (!is_array($phones['phone'])) {
                data_set($xmlArray, "{$key}.phones.phone", [$phones['phone']]);
            }
        }

        $rules = [
            '*.personid' => 'numeric',
            '*.personname' => 'string',
            '*.phones.phone.*' => 'numeric',
        ];

        $validator = Validator::make($xmlArray, $rules);

        return $validator->validate();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute xml data is invalid.';
    }
}
