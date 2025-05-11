<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules', 'module_id')->onDelete('cascade');
             $table->integer('grade')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->integer('correct_answers')->default(0);
            $table->integer('total_questions')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0.00);
        
            $table->timestamps();

            
            $table->unique(['user_id', 'module_id']); // Ensures one record per user per module
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_progress');
    }
};