<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $shop = Shop::factory()->make();

        return [
            'first_name' => $this->faker->name(),
            'surname' => $this->faker->lastName(),
            'shop_id' => $shop->id,
        ];

    }
}
