<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('villages.csv');
        
        if (!file_exists($filePath)) {
            $this->command->error('CSV file not found at: ' . $filePath);
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Read headers

        // Column mapping based on CSV header: village_name,pincode,post_so_name,taluka name,District_name,state name
        $columnMap = [
            'village_name' => 0,
            'pincode' => 1,
            'post_so_name' => 2,
            'taluka name' => 3,
            'District_name' => 4,
            'state name' => 5,
        ];

        $villages = [];
        $batchSize = 1000;
        $count = 0;

        $this->command->info('Seeding Village Master Data...');

        while (($data = fgetcsv($file)) !== false) {
            // Cleanup #N/A and spaces
            $village = array_map(fn($v) => $v === '#N/A' ? null : trim($v), $data);

            if (empty($village[$columnMap['village_name']])) continue;

            $villages[] = [
                'uuid' => (string) Str::uuid(),
                'village_name' => $village[$columnMap['village_name']],
                'pincode' => $village[$columnMap['pincode']],
                'post_so_name' => $village[$columnMap['post_so_name']] ?? null,
                'taluka_name' => $village[$columnMap['taluka name']] ?? null,
                'district_name' => $village[$columnMap['District_name']] ?? null,
                'state_name' => $village[$columnMap['state name']] ?? null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $count++;

            if (count($villages) >= $batchSize) {
                Village::insert($villages);
                $villages = [];
                $this->command->comment("Loaded $count records...");
            }
        }

        if (count($villages) > 0) {
            Village::insert($villages);
        }

        fclose($file);
        $this->command->info("Village Master Sync Completed: $count records registered.");
    }
}
