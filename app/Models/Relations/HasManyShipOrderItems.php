<?php

namespace App\Models\Relations;

use App\Models\ShipOrderItem;

trait HasManyShipOrderItems
{
    /**
     * Represents a database relationship.
     *
     * @return BelongsTo|Builder|ShipOrderItem[]|ShipOrderItem
     */
    public function items()
    {
        return $this->hasMany(ShipOrderItem::class);
    }
}

