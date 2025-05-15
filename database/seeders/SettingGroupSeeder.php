<?php

namespace Database\Seeders;

use App\Models\SettingGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'key' => 'general',
                'name' => 'Cấu hình chung',
                'description' => 'Cấu hình chung của hệ thống',
            ],
            [
                'key' => 'api',
                'name' => 'Khóa API',
                'description' => 'Cấu hình khóa API cho hệ thống',
            ],
            [
                'key' => 'info',
                'name' => 'Thông tin liên hệ',
                'description' => 'Cấu hình thông tin liên hệ cho hệ thống',
            ],
            [
                'key' => 'limit',
                'name' => 'Giới hạn',
                'description' => 'Cấu hình giới hạn cho hệ thống',
            ],
            [
                'key' => 'notification',
                'name' => 'Thông báo',
                'description' => 'Cấu hình thông báo cho hệ thống',
            ],
            [
                'key' => 'menu',
                'name' => 'Cấu hình menu',
                'description' => 'Cấu hình menu cho hệ thống',
            ],
        ];

        foreach ($groups as $group) {
            SettingGroup::updateOrCreate([
                'key' => $group['key'],
            ], $group);
        }
    }
}
