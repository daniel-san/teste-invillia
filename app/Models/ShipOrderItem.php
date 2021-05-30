<?php

namespace App\Models;

use App\Models\Relations\BelongsToShipOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipOrderItem extends Model
{
    use HasFactory;
    use BelongsToShipOrder;

    protected $fillable = [
        'title',
        'note',
        'quantity',
        'price',
    ];
}
