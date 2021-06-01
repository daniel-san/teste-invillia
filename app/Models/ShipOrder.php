<?php

namespace App\Models;

use App\Contracts\XmlDtoGeneratorContract;
use App\Models\Relations\BelongsToPerson;
use App\Models\Relations\HasManyShipOrderItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipOrder extends Model implements XmlDtoGeneratorContract
{
    use HasFactory;
    use BelongsToPerson;
    use HasManyShipOrderItems;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'person_id',
    ];

    public function address()
    {
        return $this->hasOne(ShipOrderAddress::class);
    }

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
     * @param SimpleXMLElement $shipOrderXml
     * @return array
     */
    public function getAttributesFromXml($shipOrderXml)
    {
        $address = [
            'name' => $shipOrderXml->shipto->name,
            'address' => $shipOrderXml->shipto->address,
            'city' => $shipOrderXml->shipto->city,
            'country' => $shipOrderXml->shipto->country,
        ];

        $items = [];

        foreach ($shipOrderXml->items->item as $item) {
            $items[] = [
                'title' => $item->title,
                'note' => $item->note,
                'quantity' => intval($item->quantity),
                'price' => floatval($item->price),
            ];
        }

        return [
            'id' => $shipOrderXml->orderid,
            'person_id' => $shipOrderXml->orderperson,
            'address' => $address,
            'items' => $items
        ];
    }
}
