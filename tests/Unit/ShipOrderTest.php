<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Models\ShipOrder;
use App\Models\ShipOrderAddress;
use App\Models\ShipOrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_a_person()
    {
        $shipOrder = ShipOrder::factory()
            ->for(Person::factory())
            ->create();

        $this->assertInstanceOf(Person::class, $shipOrder->person);
    }

    public function test_it_has_one_address()
    {
        $shipOrder = ShipOrder::factory()
            ->for(Person::factory())
            ->hasAddress()
            ->create();

        $this->assertInstanceOf(ShipOrderAddress::class, $shipOrder->address);
    }

    public function test_it_has_many_items()
    {
        $shipOrder = ShipOrder::factory()
            ->for(Person::factory())
            ->hasItems(3)
            ->create();

        $this->assertCount(3, $shipOrder->items);
        $this->assertContainsOnlyInstancesOf(ShipOrderItem::class, $shipOrder->items);
    }

    public function test_it_can_be_soft_deleted()
    {
        $shipOrder = ShipOrder::factory()
            ->for(Person::factory())
            ->create();

        $shipOrder->delete();

        $this->assertNotNull($shipOrder->deleted_at);
    }
}
