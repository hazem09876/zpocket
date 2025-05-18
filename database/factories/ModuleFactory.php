<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{
    protected $model = Module::class;

    public function definition()
    {
        return [
            'module_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            // admin_id is set in seeder
        ];
    }
}