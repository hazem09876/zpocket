<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id('score_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules', 'module_id')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes', 'quiz_id')->onDelete('cascade');
            $table->foreignId('level_id')->constrained('levels', 'level_id')->onDelete('cascade');
            $table->integer('grade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};