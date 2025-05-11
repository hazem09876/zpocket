<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->boolean('is_auto_calculated')->default(true)->after('grade');
            $table->timestamp('calculated_at')->nullable()->after('is_auto_calculated');
        });
    }

    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn(['is_auto_calculated', 'calculated_at']);
        });
    }
};