<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class ClientFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('pt_BR')->name(),
            'email' => fake()->email(),
            'cpf' => fake()->creditCardNumber(),
            'birth_date' => fake()->date(),
            'gender' => rand(1, 2) == 1 ? 'M' : 'F',
            'address' => fake('pt_BR')->streetAddress(),
            'city_id' => rand(47103 , 52696),
        ];
    }
}
