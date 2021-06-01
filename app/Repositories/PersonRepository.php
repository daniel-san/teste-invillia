<?php

namespace App\Repositories;

use App\Models\Person;
use App\Models\Phone;

class PersonRepository extends Repository
{
    /**
     * Type of the resource to manage.
     *
     * @var string
     */
    protected $resourceType = Person::class;

    /**
     * Handles model after save.
     *
     * @param Person $resource
     * @param array $attributes
     * @return Person
     */
    public function afterSave($resource, $attributes)
    {
        $phones = data_get($attributes, 'phones', []);

        $newPhoneIds = [];

        foreach ($phones as $phone) {
            $newPhone = new Phone();

            $newPhone->person_id = $resource->id;
            $newPhone->number = data_get($phone, 'number');

            $newPhone->save();

            $newPhoneIds[] = $newPhone->id;
        }

        $resource->phones()->whereNotIn('id', $newPhoneIds)->delete();

        return $resource;
    }
}
