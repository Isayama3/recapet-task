<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
            [
                'key' => 'main_config',
                'title' => 'ride_request_price_percentage',
                'value' => '2',
            ],
            [
                'key' => 'main_config',
                'title' => 'driver_wallet_max_balance',
                'value' => '1000',
            ],
        ]);
    }
}
