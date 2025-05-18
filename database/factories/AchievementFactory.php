<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(8),
            'date_achieved' => $this->faker->dateTimeThisMonth(),
            // user_id set in seeder
        ];
    }
}