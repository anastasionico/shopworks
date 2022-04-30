<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class RotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $shop = Shop::factory()->make();

        $date = new \DateTime();
        $nextMonday = $date->modify('next monday');


        return [
            'shop_id' => $shop->id,
            'week_commence_date' => $nextMonday
        ];
    }
}
