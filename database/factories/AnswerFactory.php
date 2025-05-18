<?php

namespace Database\Factories;

use App\Models\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition()
    {
        return [
            'answer_text' => $this->faker->sentence(3),
            'is_correct' => false,
            // question_id, user_id set in seeder
        ];
    }
}