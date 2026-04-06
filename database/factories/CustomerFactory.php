<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $category = $this->faker->randomElement(['individual', 'business']);

        return [
            // 🔹 Primary
            'uuid' => (string) Str::uuid(),
            'customer_code' => 'CUST-' . strtoupper($this->faker->bothify('??######')),

            // 🔹 Identity
            'first_name' => $firstName,
            'last_name' => $lastName,
            'display_name' => "{$firstName} {$lastName}",

            'mobile' => $this->faker->unique()->numerify('9#########'),
            'email' => $this->faker->unique()->safeEmail,
            'alternate_email' => $this->faker->optional()->safeEmail,
            'whatsapp_number' => $this->faker->numerify('9#########'),

            'phone_number_2' => $this->faker->optional()->numerify('9#########'),
            'relative_phone' => $this->faker->optional()->numerify('9#########'),

            'source' => $this->faker->randomElement(['referral', 'marketing', 'walk-in', 'social-media', 'agent']),

            // 🔹 Classification
            'type' => $this->faker->randomElement(['farmer', 'buyer', 'vendor', 'dealer']),
            'category' => $category,
            'customer_group' => $this->faker->randomElement(['Regular', 'Premium', 'Wholesale', 'Institutional']),

            // 🔹 Business
            'company_name' => $category === 'business' ? $this->faker->company : null,
            'gst_number' => $category === 'business'
                ? strtoupper($this->faker->bothify('##?????####?#?#'))
                : null,
            'pan_number' => strtoupper($this->faker->bothify('?????####?')),

            // 🔹 Agriculture
            'land_area' => $this->faker->optional()->randomFloat(2, 0.5, 50.0),
            'land_unit' => $this->faker->randomElement(['acre', 'bigha', 'hectare']),
            'crops' => $this->faker->randomElements(['Wheat', 'Cotton', 'Mustard', 'Bajra'], rand(1, 3)),
            'irrigation_type' => $this->faker->randomElement(['Tube Well', 'Canal', 'Rain-fed', 'Drip']),

            // 🔹 Financial
            'credit_limit' => $this->faker->randomFloat(2, 10000, 500000),
            'outstanding_balance' => $this->faker->randomFloat(2, 0, 50000),
            'overdue_amount' => $this->faker->randomFloat(2, 0, 5000),
            'credit_score' => $this->faker->optional()->numberBetween(300, 900),
            'payment_terms_days' => $this->faker->randomElement([15, 30, 45, 60]),
            'credit_valid_till' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),

            'last_payment_date' => $this->faker->optional()->dateTimeBetween('-6 months', 'now'),

            'lifetime_value' => $this->faker->randomFloat(2, 50000, 1000000),
            'average_order_value' => $this->faker->randomFloat(2, 5000, 50000),

            // 🔹 KYC
            'aadhaar_last4' => $this->faker->optional()->numerify('####'),
            'aadhaar_number_hash' => $this->faker->optional()->sha256,
            'kyc_completed' => $this->faker->boolean(70),
            'kyc_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'kyc_verified_at' => $this->faker->optional()->dateTime(),

            'pan_verified' => $this->faker->boolean(80),
            'gst_verified' => $this->faker->boolean(70),

            // 🔹 Engagement
            'first_purchase_at' => $this->faker->optional()->dateTimeBetween('-2 years', '-6 months'),
            'last_purchase_at' => $this->faker->optional()->dateTimeBetween('-6 months', 'now'),
            'orders_count' => $this->faker->numberBetween(0, 100),
            'last_contacted_at' => $this->faker->optional()->dateTimeBetween('-3 months', 'now'),

            'lead_status' => $this->faker->randomElement(['lead', 'converted', 'inactive']),

            // 🔹 Status
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'is_blacklisted' => $this->faker->boolean(5),

            // 🔹 Meta
            'internal_notes' => $this->faker->optional()->sentence(),
            'tags' => $this->faker->randomElements(['Premium', 'Loyal', 'High-Risk'], rand(1, 3)),
            'meta' => [
                'source_detail' => $this->faker->word(),
                'notes' => $this->faker->sentence(),
            ],

            // 🔹 Relations
            'assigned_to' => User::inRandomOrder()->value('id') ?? User::factory(),
            'referred_by' => null, // avoid recursion issue
            'primary_address_id' => null,

            // 🔹 Audit
            'created_by' => User::inRandomOrder()->value('id') ?? User::factory(),
            'updated_by' => null,
            'deleted_by' => null,

            // 🔹 Auth
            'last_login_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'otp_verified_at' => $this->faker->optional()->dateTime(),

            // 🔹 Timestamps
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}