<?php

namespace App\Models\Relations;

use App\Models\Person;

trait BelongsToPerson
{
    /**
     * Represents a database relationship.
     *
     * @return BelongsTo|Builder|Person
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
