<?php

namespace Database\Factories;

use App\Models\Rota;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rota = Rota::factory()->make();
        $staff = Staff::factory()->make();

        return [
            'rota_id' => $rota->id,
            'staff_id' => $staff->id,
            'start_time' => $this->faker->dateTime('now', null),
            'end_time' => $this->faker->dateTime('now', null),
        ];
    }
}
