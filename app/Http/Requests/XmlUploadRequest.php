<?php

namespace App\Http\Requests;

use App\Rules\PeopleXml;
use App\Rules\ValidXml;
use Illuminate\Foundation\Http\FormRequest;

class XmlUploadRequest extends FormRequest
{
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
            'people' => ['required', 'file', 'mimes:xml', new ValidXml, new PeopleXml],
            'shiporders' => ['required', 'mimes:xml', new ValidXml],
        ];

    }
}
