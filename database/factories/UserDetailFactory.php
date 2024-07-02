<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'age' => $this->faker->randomDigit(),
        ];
    }
}
