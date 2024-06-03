<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "full_name"=>fake()->name,
            "company"=>fake()->text,
            "phone-number"=>fake()->phoneNumber,
            "email"=>fake()->safeEmail(),
            "date_birth"=>fake()->date,
            "path_to_photo"=>null,
        ];
    }
}
