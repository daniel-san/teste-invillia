<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        } catch (ValidationException $e) {
            return false;
        }

        return true;
    }

    protected function validateXml($xml)
    {
        $parsed = json_encode(simplexml_load_string($xml));
        $arr = json_decode($parsed, true);

        $arr = collect($arr)->first();

        foreach($arr as $key => $value) {
            $phones = data_get($value, 'phones');

            if (!is_array($phones['phone'])) {
                data_set($arr, "{$key}.phones.phone", [$phones['phone']]);
            }
        }

        $rules = [
            '*.personid' => 'numeric',
            '*.personname' => 'string',
            '*.phones.phone.*' => 'numeric',
        ];

        $validator = Validator::make($arr, $rules);

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
