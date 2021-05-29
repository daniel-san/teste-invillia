<?php

namespace App\Models;

use App\Models\Relations\BelongsToPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;
    use BelongsToPerson;

    protected $fillable = [
        'number'
    ];
}
