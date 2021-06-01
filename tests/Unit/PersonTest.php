<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Models\Phone;
use App\Models\ShipOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_many_phones()
    {
        $person = Person::factory()->hasPhones(3)->create();

        $this->assertCount(3, $person->phones);
        $this->assertContainsOnlyInstancesOf(Phone::class, $person->phones);
    }

    public function test_it_has_many_ship_orders()
    {
        $person = Person::factory()->hasShipOrders(3)->create();

        $this->assertCount(3, $person->shipOrders);
        $this->assertContainsOnlyInstancesOf(ShipOrder::class, $person->shipOrders);
    }

    public function test_it_can_be_soft_deleted()
    {
        $person = Person::factory()->create();
        $person->delete();

        $this->assertNotNull($person->deleted_at);
    }
}
