<?php

namespace App\Repositories;

use App\Models\ShipOrder;

class ShipOrderRepository extends Repository
{
    /**
     * Type of the resource to manage.
     *
     * @var string
     */
    protected $resourceType = ShipOrder::class;

    /**
     * Handles model after save.
     *
     * @param ShipOrder $resource
     * @param array $attributes
     * @return ShipOrder
     */
    public function afterSave($resource, $attributes)
    {
        $address = data_get($attributes, 'address');

        $items = data_get($attributes, 'items', []);

        if ($resource->address()->exists() && $address) {
            $resource->address()->update($address);
        } elseif ($address) {
            $resource->address()->create($address);
        }

        $itemIds = [];
        foreach($items as $item) {
            $item = $resource->items()->create($item);
            $itemIds[] = $item->id;
        }

        $resource->items()->whereNotIn('id', $itemIds)->delete();

        return $resource;
    }
}
