<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CardChallengeFactory extends Factory
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
            'title' => $this->faker->words(1, true),
            'buying_power' => $this->faker->randomNumber(5, true),
            'price' => $this->faker->randomNumber(3, true),
            'is_feature' => 0,
        ];
    }
}
