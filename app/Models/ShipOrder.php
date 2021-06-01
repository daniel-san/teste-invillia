<?php

namespace App\Models;

use App\Models\Relations\BelongsToPerson;
use App\Models\Relations\HasManyShipOrderItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipOrder extends Model
{
    use HasFactory;
    use BelongsToPerson;
    use HasManyShipOrderItems;

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
    public static function attributesFromXml($shipOrderXml)
    {
        $address = [
            'name' => $shipOrderXml->shipto->name,
            'address' => $shipOrderXml->shipto->address,
            'city' => $shipOrderXml->shipto->city,
            'country' => $shipOrderXml->shipto->country,
        ];

        $items = [];

        foreach($shipOrderXml->items->item as $item) {
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
