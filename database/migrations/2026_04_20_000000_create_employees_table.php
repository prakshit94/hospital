<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('employee_id')->nullable(); // Company-specific ID
            $table->string('full_name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('father_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('husband_name')->nullable();
            $table->string('dependent')->nullable();
            $table->text('address')->nullable();
            $table->string('identification_mark')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->text('habits')->nullable();
            $table->text('prev_occ_history')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
