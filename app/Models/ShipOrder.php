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
    ];

    public function address()
    {
        return $this->hasOne(ShipOrderAddress::class);
    }
}
