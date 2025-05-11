<?php

namespace Database\Factories;

use App\Models\Score;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    protected $model = Score::class;

    public function definition()
    {
        return [
            'user_id' => null, // set in seeder
            'module_id' => null, // set in seeder
            'question_id' => null, // set in seeder
            'grade' => $this->faker->numberBetween(0, 100),
        ];
    }
}