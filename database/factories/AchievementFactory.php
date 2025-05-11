<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\User; // <-- Add this import!
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->user_id ?? 1, // fallback to 1 if no users exist
            'date_achieved' => $this->faker->date(),
            'description' => $this->faker->sentence(8),
        ];
    }
}