<?php

namespace Database\Factories;

use App\Models\UserProgress;
use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProgressFactory extends Factory
{
    protected $model = UserProgress::class;

    public function definition()
    {
        return [
            'user_progress_id' => $this->faker->unique()->randomNumber(),
            'user_id' => User::factory(),
            'module_id' => Module::factory(),
            'progress' => $this->faker->numberBetween(0, 100),
        ];
    }
}