<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->colorName(),
            'url' => $this->faker->url(),
            'slug' => $this->faker->unique()->lexify('??????'),
            'user_id' => $this->faker->numberBetween(1,40)
        ];
    }
}
