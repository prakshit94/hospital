<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('health_checkups', function (Blueprint $table) {
            $table->text('near_vision_right_without')->nullable()->after('near_vision_right');
            $table->text('distant_vision_right_without')->nullable()->after('distant_vision_right');
            $table->text('near_vision_left_without')->nullable()->after('near_vision_left');
            $table->text('distant_vision_left_without')->nullable()->after('distant_vision_left');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_checkups', function (Blueprint $table) {
            $table->dropColumn([
                'near_vision_right_without',
                'distant_vision_right_without',
                'near_vision_left_without',
                'distant_vision_left_without',
            ]);
        });
    }
};
