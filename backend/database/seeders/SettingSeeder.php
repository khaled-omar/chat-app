<?php

namespace Database\Seeders;

use App\Enums\SettingKey;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::query()->delete();

        $settings = [
            [
                'key' => SettingKey::PRIVACY_POLICY,
                'value' => 'Privacy Policy',
            ],
            [
                'key' => SettingKey::TERMS_AND_CONDITIONS,
                'value' => 'Terms And Conditions',
            ],
        ];

        foreach ($settings as $setting) {
            $createdSetting = Setting::query()->create(['key' => $setting['key'], 'lang_code' => 'en']);
            $createdSetting->languages()->create([
                'value' => $setting['value'],
                'lang_code' => 'en',
            ]);
        }
    }
}
