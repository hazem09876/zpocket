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
            'question_id' => null, // set in seeder
            'answer_text' => $this->faker->sentence(3),
            'is_correct' => false, // set in seeder
            'user_answer' => 0,
        ];
    }
}