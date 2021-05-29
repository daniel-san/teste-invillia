<?php

namespace App\Models;

use App\Models\Relations\BelongsToShipOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipOrderAddress extends Model
{
    use HasFactory;
    use BelongsToShipOrder;

    protected $fillable = [
        'name',
        'address',
        'city',
        'country'
    ];
}
