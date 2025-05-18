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
            // user_id, module_id set in seeder
        ];
    }
}