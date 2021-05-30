<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'person' => new PersonResource($this->whenLoaded('person')),
            'address' => new ShipOrderAddressResource($this->whenLoaded('address')),
            'items' => ShipOrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function with($request)
    {
        return [
            'links' => [
                'self' => route('api.ship-orders.index'),
            ],
        ];
    }
}
