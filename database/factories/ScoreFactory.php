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
            'grade' => $this->faker->numberBetween(0, 100),
            // user_id, module_id, question_id set in seeder
        ];
    }
}