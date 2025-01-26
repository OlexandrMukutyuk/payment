<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = Settings::firstOrCreate([
            'admin_name' => 'Ліміт',
            'key' => 'limit',

        ],[
            'value' => '100000',
        ]);
    }
}
