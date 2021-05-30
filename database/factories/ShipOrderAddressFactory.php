<?php

namespace Database\Factories;

use App\Models\ShipOrder;
use App\Models\ShipOrderAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipOrderAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShipOrderAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ship_order_id' => ShipOrder::factory(),
            'name' => $this->faker->name,
            'address' => $this->faker->sentence,
            'city' => $this->faker->city,
            'country' => $this->faker->country
        ];
    }
}
