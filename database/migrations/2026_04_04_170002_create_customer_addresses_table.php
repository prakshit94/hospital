<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->enum('type', ['billing', 'shipping', 'both'])->default('shipping');
            $table->string('label')->nullable();

            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();

            $table->string('address_line1');
            $table->string('address_line2')->nullable();

            $table->string('landmark')->nullable();
            $table->text('full_address')->nullable();

            $table->foreignId('village_id')->nullable()->constrained('villages')->nullOnDelete();

            $table->string('village')->nullable();
            $table->string('taluka')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable()->index();

            $table->string('country')->default('India');
            $table->string('pincode')->nullable();
            $table->string('post_office')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->text('delivery_instructions')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            $table->string('route_code')->nullable();
            $table->string('zone')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });

        // ✅ Add FK AFTER both tables exist
        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('primary_address_id')
                ->references('id')
                ->on('customer_addresses')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['primary_address_id']);
        });

        Schema::dropIfExists('customer_addresses');
    }
};