<?php

namespace Database\Factories;

use App\Models\Feedback;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition()
    {
        return [
            'user_id' => null, // set in seeder
            'module_id' => null, // set in seeder
            'content' => $this->faker->sentence(12),
        ];
    }
}