<?php

namespace App\Models\Relations;

use App\Models\Phone;

trait HasManyPhones
{
    /**
     * Represents a database relationship.
     *
     * @return BelongsTo|Builder|Phone[]|Phone
     */
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
}
