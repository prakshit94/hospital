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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_id')->index(); // A unique identifier for the browser/device
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->timestamp('last_active_at')->useCurrent();
            $table->boolean('is_trusted')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
