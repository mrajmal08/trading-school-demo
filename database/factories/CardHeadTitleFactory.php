<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CardHeadTitleFactory extends Factory
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
            'title' => $this->faker->name(),
        ];
    }
}
