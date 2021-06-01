<?php

namespace App\Models;

use App\Models\Relations\HasManyPhones;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
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
     * @param SimpleXMLElement $personXml
     * @return array
     */
    public static function attributesFromXml($personXml)
    {
        $phones = [];

        foreach($personXml->phones->phone as $phone) {
            $phones[] = ['number' => $phone];
        }

        return [
            'id' => $personXml->personid,
            'name' => $personXml->personname,
            'phones' => $phones
        ];
    }
}
