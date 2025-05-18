<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'),
            'date_of_birth' => $this->faker->date('Y-m-d', '-18 years'),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}