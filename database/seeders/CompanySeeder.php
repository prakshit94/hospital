<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Global Industries Ltd.',
                'code' => 'GIL',
                'address' => 'Industrial Area, Phase 1, City North',
                'contact_person' => 'John Doe',
                'contact_number' => '9876543210',
                'email' => 'contact@globalind.com',
                'is_active' => true,
            ],
            [
                'name' => 'Tech Solutions Corp',
                'code' => 'TSC',
                'address' => 'IT Park, Sector 5, Silicon Valley',
                'contact_person' => 'Jane Smith',
                'contact_number' => '9123456780',
                'email' => 'hr@techsolutions.com',
                'is_active' => true,
            ],
            [
                'name' => 'Zenith Manufacturing',
                'code' => 'ZMF',
                'address' => 'MIDC Area, Plot 45, Industrial Belt',
                'contact_person' => 'Robert Brown',
                'contact_number' => '9988776655',
                'email' => 'admin@zenithmfg.com',
                'is_active' => true,
            ],
            [
                'name' => 'Sunrise Healthcare',
                'code' => 'SHC',
                'address' => 'Healthcare Plaza, 12th Cross, Medical City',
                'contact_person' => 'Dr. Alice Wong',
                'contact_number' => '9845012345',
                'email' => 'ops@sunrisehealth.com',
                'is_active' => true,
            ],
            [
                'name' => 'Blue Ocean Logistics',
                'code' => 'BOL',
                'address' => 'Port Authority Bldg, Dockyard Road',
                'contact_person' => 'Michael Chang',
                'contact_number' => '9765432109',
                'email' => 'fleet@blueocean.com',
                'is_active' => true,
            ],
            [
                'name' => 'NexGen Energy',
                'code' => 'NGE',
                'address' => 'Power House, Energy Square, Capital City',
                'contact_person' => 'Sarah Jenkins',
                'contact_number' => '9123443210',
                'email' => 'facilities@nexgen.com',
                'is_active' => false,
            ],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(['name' => $company['name']], $company);
        }
    }
}
