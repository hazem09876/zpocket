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
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create users first
        $users = User::factory(5)->create();
        
        // Create admins with explicit user_id references
        $admins = collect();
        foreach ($users as $user) {
            $admins->push(Admin::factory()->create([
                'user_id' => $user->user_id,
                'permission' => 'full',
            ]));
        }
        
        // Create modules with explicit admin_id references
        $modules = collect();
        foreach ($admins as $admin) {
            $modules->push(Module::factory()->create([
                'admin_id' => $admin->admin_id,
            ]));
        }
        
        // Create achievements with explicit user_id references
        foreach ($users as $user) {
            Achievement::factory()->create([
                'user_id' => $user->user_id,
            ]);
        }
        
        // Create videos with explicit module_id references
        foreach ($modules as $module) {
            Video::factory(rand(1, 3))->create([
                'module_id' => $module->module_id,
            ]);
        }
        
        // Create questions with explicit module_id references
        $questions = collect();
        foreach ($modules as $module) {
            for ($i = 0; $i < rand(3, 5); $i++) {
                $questions->push(Question::factory()->create([
                    'module_id' => $module->module_id,
                ]));
            }
        }
        
        // Bulk insert answers with explicit question_id references
        $answers = [];
        foreach ($questions as $question) {
            $correctIndex = rand(0, 3); // One of four answers will be correct
            for ($i = 0; $i < 4; $i++) {
                $answers[] = Answer::factory()->make([
                    'question_id' => $question->question_id,
                    'is_correct' => $i === $correctIndex,
                ])->toArray();
            }
        }
        Answer::insert($answers);
        
        // Create user-module registrations with explicit user_id and module_id references
        $userModules = collect();
        foreach ($users as $user) {
            $userModuleCount = rand(1, $modules->count());
            $selectedModules = $modules->random($userModuleCount);
            
            foreach ($selectedModules as $module) {
                $userModules->push(UserModule::factory()->create([
                    'user_id' => $user->user_id,
                    'module_id' => $module->module_id,
                ]));
            }
        }
        
        // Create feedback with explicit user_id and module_id references
        foreach ($users as $user) {
            $feedbackModules = $modules->random(min(2, $modules->count()));
            foreach ($feedbackModules as $module) {
                Feedback::factory()->create([
                    'user_id' => $user->user_id,
                    'module_id' => $module->module_id,
                ]);
            }
        }
        
        // Bulk insert scores with explicit user_id, module_id, and question_id references
        $scores = [];
        foreach ($userModules as $userModule) {
            $questionsInModule = $questions->where('module_id', $userModule->module_id);
            
            foreach ($questionsInModule as $question) {
                $scores[] = Score::factory()->make([
                    'user_id' => $userModule->user_id,
                    'module_id' => $userModule->module_id,
                    'question_id' => $question->question_id,
                    'grade' => rand(0, 100),
                ])->toArray();
            }
        }
        Score::insert($scores);
    }
}
