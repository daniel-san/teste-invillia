<?php

namespace App\Models\Relations;

use App\Models\ShipOrderItem;

trait HasManyShipOrderItems
{
    public function items()
    {
        return $this->hasMany(ShipOrderItem::class);
    }
}

