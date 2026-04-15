<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_name'            => config('app.company_name', ''),
            'company_address'         => config('app.address', ''),
            'company_address_pluscode' => config('app.address_pluscode', ''),
            'company_phone'           => config('app.phone', ''),
            'company_email'           => config('app.email', ''),
            'company_facebook'        => config('app.facebook', ''),
            'company_zalo'            => config('app.zalo', ''),
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
