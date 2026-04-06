<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('village_name')->index();
            $table->string('pincode')->index();
            $table->string('post_so_name')->nullable();

            $table->string('taluka_name')->nullable();
            $table->string('district_name')->nullable();
            $table->string('state_name')->nullable();

            $table->string('state_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('taluka_code')->nullable();
            $table->string('village_code')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->boolean('is_serviceable')->default(true);
            $table->json('delivery_days')->nullable();
            $table->integer('priority')->default(0);

            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};