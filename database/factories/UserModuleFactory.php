<?php

namespace Database\Factories;

use App\Models\UserModule;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserModuleFactory extends Factory
{
    protected $model = UserModule::class;

    public function definition()
    {
        return [
            'user_id' => null, // set in seeder
            'module_id' => null, // set in seeder
        ];
    }
}