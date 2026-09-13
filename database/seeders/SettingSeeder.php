<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $defaultSettings = [
            // General Meta Tags Config Mapping Contexts
            [
                'key' => 'app_name',
                'value' => 'Quality Assurance Monitoring and Evaluation',
                'group' => 'general'
            ],
            [
                'key' => 'contact_email',
                'value' => 'sdodavsur@gmail.com',
                'group' => 'general'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'group' => 'general'
            ],
            [
                'key' => 'allow_registration',
                'value' => '1',
                'group' => 'general'
            ],

            // Transport Mail Layer SMTP Mapping Contexts
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'group' => 'mail'
            ],
            [
                'key' => 'mail_host',
                'value' => 'sandbox.smtp.mailtrap.io',
                'group' => 'mail'
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'group' => 'mail'
            ],
            [
                'key' => 'mail_username',
                'value' => '8ea0568d116c3c',
                'group' => 'mail'
            ],
            [
                'key' => 'mail_password',
                'value' => '470f647d0de67d',
                'group' => 'mail'
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'no-reply@sdodavsur.com',
                'group' => 'mail'
            ],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
