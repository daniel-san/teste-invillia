<?php

namespace Database\Factories;

use App\Models\ShipOrder;
use App\Models\ShipOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipOrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShipOrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ship_order_id' => ShipOrder::factory(),
            'title' => $this->faker->title,
            'note' => $this->faker->sentence,
            'quantity' => $this->faker->numberBetween(1, 1000),
            'price' => $this->faker->randomFloat(4, 0)
        ];
    }
}
