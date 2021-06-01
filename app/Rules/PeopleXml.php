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
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Verify if the passed xml is valid.
     *
     * @param string $xml
     * @throws ValidationException
     * @return array
     */
    protected function validateXml($xml)
    {
        $peopleArray = $this->prepareDataForValidation($xml);

        $rules = [
            '*.personid' => 'numeric',
            '*.personname' => 'string',
            '*.phones.phone.*' => 'numeric',
        ];

        $validator = Validator::make($peopleArray, $rules);

        return $validator->validate();
    }

    /**
     * Prepare the data from the xml for validation.
     *
     * @param string $xml
     * @return array
     */
    protected function prepareDataForValidation($xml)
    {
        $parsedPeople = json_encode(simplexml_load_string($xml));
        $peopleArray = json_decode($parsedPeople, true);

        $peopleArray = collect($peopleArray)->first();

        foreach ($peopleArray as $key => $person) {
            $phones = data_get($person, 'phones');

            if (!is_array($phones['phone'])) {
                data_set($peopleArray, "{$key}.phones.phone", [$phones['phone']]);
            }
        }

        return $peopleArray;
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
