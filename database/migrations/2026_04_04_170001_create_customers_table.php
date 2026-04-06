<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('customer_code')->unique();

            // Identity
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();

            $table->string('mobile', 20)->unique();
            $table->string('email')->nullable()->index();
            $table->string('alternate_email')->nullable();
            $table->string('whatsapp_number', 20)->nullable();

            $table->string('phone_number_2', 20)->nullable();
            $table->string('relative_phone', 20)->nullable();

            $table->string('source')->nullable();

            // Classification
            $table->enum('type', ['farmer', 'buyer', 'vendor', 'dealer'])->default('farmer');
            $table->enum('category', ['individual', 'business'])->default('individual');
            $table->string('customer_group')->nullable();

            // Business
            $table->string('company_name')->nullable();
            $table->string('gst_number', 20)->nullable()->index();
            $table->string('pan_number', 10)->nullable();

            // Agriculture
            $table->decimal('land_area', 10, 2)->nullable();
            $table->string('land_unit')->default('acre');
            $table->json('crops')->nullable();
            $table->string('irrigation_type')->nullable();

            // Financial
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('outstanding_balance', 12, 2)->default(0);
            $table->decimal('overdue_amount', 12, 2)->default(0);
            $table->integer('credit_score')->nullable();
            $table->integer('payment_terms_days')->nullable();
            $table->date('credit_valid_till')->nullable();
            $table->date('last_payment_date')->nullable();

            $table->decimal('lifetime_value', 12, 2)->default(0);
            $table->decimal('average_order_value', 12, 2)->default(0);

            // KYC
            $table->string('aadhaar_last4', 4)->nullable();
            $table->string('aadhaar_number_hash')->nullable();
            $table->boolean('kyc_completed')->default(false);
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('kyc_verified_at')->nullable();
            $table->text('kyc_rejected_reason')->nullable();

            $table->boolean('pan_verified')->default(false);
            $table->boolean('gst_verified')->default(false);

            // Engagement
            $table->date('first_purchase_at')->nullable();
            $table->date('last_purchase_at')->nullable();
            $table->unsignedInteger('orders_count')->default(0);
            $table->timestamp('last_contacted_at')->nullable();

            $table->enum('lead_status', ['lead', 'converted', 'inactive'])->default('lead');

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->boolean('is_blacklisted')->default(false);

            // Meta
            $table->text('internal_notes')->nullable();
            $table->json('tags')->nullable();
            $table->json('meta')->nullable();

            // IMPORTANT: no FK here yet (added in next migration)
            $table->unsignedBigInteger('primary_address_id')->nullable();

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('referred_by')->nullable()->constrained('customers')->nullOnDelete();

            $table->string('external_id')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['mobile', 'status']);
            $table->index(['type', 'category']);
            $table->index(['assigned_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};