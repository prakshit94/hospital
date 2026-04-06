<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have at least one user to assign to
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'System Admin',
                'email' => 'admin@system.com',
                'status' => 'active',
            ]);
        }

        // Create 50 dummy customers
        Customer::factory()->count(50)->create();

        $this->command->info('Created 50 dummy customers with full profiling details.');
    }
}
