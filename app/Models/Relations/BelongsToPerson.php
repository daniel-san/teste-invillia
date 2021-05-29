<?php

namespace App\Models\Relations;

use App\Models\Person;

trait BelongsToPerson
{
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
