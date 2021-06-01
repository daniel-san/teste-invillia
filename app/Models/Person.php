<?php

namespace App\Models;

use App\Contracts\XmlDtoGeneratorContract;
use App\Models\Relations\HasManyPhones;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model implements XmlDtoGeneratorContract
{
    use HasFactory;
    use HasManyPhones;

    protected $fillable = [
        'id',
        'name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generates a DTO with the data from a SimpleXMLElement object.
     *
     * @param SimpleXMLElement $shipOrderXml
     * @return array
     */
    public static function attributesFromXml($xml)
    {
        return (new static)->getAttributesFromXml($xml);
    }

    /**
     * Generates a DTO with the data from a SimpleXMLElement object.
     *
     * @param SimpleXMLElement $personXml
     * @return array
     */
    public function getAttributesFromXml($personXml)
    {
        $phones = [];

        foreach ($personXml->phones->phone as $phone) {
            $phones[] = ['number' => $phone];
        }

        return [
            'id' => $personXml->personid,
            'name' => $personXml->personname,
            'phones' => $phones
        ];
    }
}
