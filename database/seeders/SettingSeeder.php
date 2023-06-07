<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminSetting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = AdminSetting::create([
            'option_name' => 'auto_full_fill', 
            'option_value' => 0,
        ]);
    }
}
