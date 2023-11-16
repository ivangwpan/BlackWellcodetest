<?php

namespace Database\Seeders;

use App\Models\AddressType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        AddressType::create([
            'name' => 'Residential Address',
            'is_active' => true,
        ]);

        AddressType::create([
            'name' => 'Correspondence Address',
            'is_active' => true,
        ]);
    }
}
