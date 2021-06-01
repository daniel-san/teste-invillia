<?php

namespace App\Models\Relations;

use App\Models\ShipOrder;

trait BelongsToShipOrder
{
    /**
     * Represents a database relationship.
     *
     * @return BelongsTo|Builder|ShipOrder
     */
    public function shipOrder()
    {
        return $this->belongsTo(ShipOrder::class);
    }
}
