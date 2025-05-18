<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       
 Schema::create('admins', function (Blueprint $table) {
    $table->id('admin_id');
    $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
    $table->string('permission')->default('full'); 
    $table->timestamps();
});
       
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};