<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformTabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::insert([
            [
                'name' => 'Facebook',
                'type' => 'facebook',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Twitter',
                'type' => 'twitter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Instagram',
                'type' => 'instagram',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LinkedIn',
                'type' => 'linkedin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Snapchat',
                'type' => 'snapchat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WhatsApp',
                'type' => 'whatsapp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Telegram',
                'type' => 'telegram',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
