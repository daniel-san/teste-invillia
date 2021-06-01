<?php

namespace Tests\Unit;

use App\Models\ShipOrder;
use App\Models\ShipOrderAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipOrderAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_a_ship_order()
    {
        $shipOrderAddress = ShipOrderAddress::factory()->create();

        $this->assertInstanceOf(ShipOrder::class, $shipOrderAddress->shipOrder);
    }
}
