<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition()
    {
        return [
            // user_id is set in seeder
            'permission' => $this->faker->randomElement(['full', 'read', 'write']),
        ];
    }
}