<?php

namespace App\Models\Relations;

use App\Models\ShipOrder;

trait BelongsToShipOrder
{
    public function shipOrder()
    {
        return $this->belongsTo(ShipOrder::class);
    }
}
