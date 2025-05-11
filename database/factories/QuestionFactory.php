<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'module_id' => null, // set in seeder
            'content' => $this->faker->sentence(8),
            'type' => $this->faker->randomElement(['mcq', 'true_false']),
        ];
    }
}