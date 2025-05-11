<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition()
    {
        return [
            'module_id' => null, // set in seeder
            'embed_code' => '<iframe src="https://www.youtube.com/embed/' . $this->faker->unique()->bothify('video###') . '"></iframe>',
            'title' => $this->faker->sentence(4),
        ];
    }
}