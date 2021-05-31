<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\ShipOrder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $people = Person::factory(3)->hasPhones(2)->create();

        $people->each(function (Person $person) {
            ShipOrder::factory()->for($person)->hasAddress()->hasItems(3)->create();
        });
    }
}
