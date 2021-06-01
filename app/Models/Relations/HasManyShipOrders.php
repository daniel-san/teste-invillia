<?php

namespace App\Models\Relations;

use App\Models\ShipOrder;

trait HasManyShipOrders
{
    /**
     * Represents a database relationship.
     *
     * @return BelongsTo|Builder|ShipOrder[]|ShipOrder
     */
    public function shipOrders()
    {
        return $this->hasMany(ShipOrder::class);
    }
}
