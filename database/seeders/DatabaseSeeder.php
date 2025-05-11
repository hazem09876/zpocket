<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Module;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Score;
use App\Models\Achievement;
use App\Models\Feedback;
use App\Models\Video;
use App\Models\UserModule;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Users
        $users = User::factory(10)->create();

        // Modules
        $modules = Module::factory(5)->create();

        // Achievements
        Achievement::factory(5)->create();

        // Videos (each assigned to a random module)
        $modules->each(function ($module) {
            Video::factory(rand(2, 4))->create([
                'module_id' => $module->module_id,
            ]);
        });

        // Questions (each assigned to a random module)
        $modules->each(function ($module) {
            Question::factory(rand(5, 10))->create([
                'module_id' => $module->module_id,
            ]);
        });

        // Answers (each question gets 4 answers, one correct)
        $questions = Question::all();
        $questions->each(function ($question) {
            $correctIndex = rand(0, 3);
            for ($i = 0; $i < 4; $i++) {
                Answer::factory()->create([
                    'question_id' => $question->question_id,
                    'is_correct' => $i === $correctIndex,
                ]);
            }
        });

        // User-Module registrations
        foreach ($users as $user) {
            $userModules = $modules->random(rand(2, 4));
            foreach ($userModules as $module) {
                UserModule::factory()->create([
                    'user_id' => $user->user_id,
                    'module_id' => $module->module_id,
                ]);
            }
        }

        // Feedback (each user leaves feedback on 1-2 modules)
        foreach ($users as $user) {
            $feedbackModules = $modules->random(rand(1, 2));
            foreach ($feedbackModules as $module) {
                Feedback::factory()->create([
                    'user_id' => $user->user_id,
                    'module_id' => $module->module_id,
                ]);
            }
        }

        // Scores (for each user, for each module they are registered in, for each question in that module)
        $userModules = UserModule::all();
        foreach ($userModules as $userModule) {
            $questionsInModule = Question::where('module_id', $userModule->module_id)->get();
            foreach ($questionsInModule as $question) {
                Score::factory()->create([
                    'user_id' => $userModule->user_id,
                    'module_id' => $userModule->module_id,
                    'question_id' => $question->question_id,
                ]);
            }
        }
    }
}
