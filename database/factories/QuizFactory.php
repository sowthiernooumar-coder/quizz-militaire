<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [

            'title' => fake()->sentence(),

            'subject_id' => Subject::inRandomOrder()->first()->id,

            'level' => fake()->numberBetween(1,3),

            'created_by' => User::role(
                'instructor_l1'
            )->inRandomOrder()->first()->id,

        ];
    }
}
