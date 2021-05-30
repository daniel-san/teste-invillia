<?php

namespace Tests\Unit;

use App\Models\ShipOrder;
use App\Models\ShipOrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipOrderItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_a_ship_order()
    {
        $shipOrderItem = ShipOrderItem::factory()->create();

        $this->assertInstanceOf(ShipOrder::class, $shipOrderItem->shipOrder);
    }
}
