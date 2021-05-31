<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ShipOrdersXml implements Rule
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
        $shipOrdersXml = $value->get();

        try {
            $this->validateXml($shipOrdersXml);
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

            $items = data_get($value, 'items.item');
            if(empty(array_filter($items, 'is_array'))) {
                $normalizedItem = [[
                    'title' => $items['title'],
                    'note' => $items['note'],
                    'quantity' => $items['quantity'],
                    'price' => $items['price'],
                ]];

                data_set($xmlArray, "{$key}.items.item", $normalizedItem);
            }
        }

        $rules = [
            '*.orderid' => 'numeric',
            '*.orderperson' => 'numeric',
            '*.shipto.name' => 'string',
            '*.shipto.address' => 'string',
            '*.shipto.city' => 'string',
            '*.shipto.country' => 'string',
            '*.items.item.*.title' => 'string',
            '*.items.item.*.note' => 'string',
            '*.items.item.*.quantity' => 'numeric',
            '*.items.item.*.price' => 'numeric'
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
