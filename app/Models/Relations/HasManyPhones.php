<?php

namespace App\Models\Relations;

use App\Models\Phone;

trait HasManyPhones
{
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
}
