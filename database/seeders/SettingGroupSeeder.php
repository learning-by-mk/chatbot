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
                'name' => 'Cấu hình chung',
                'description' => 'Cấu hình chung của hệ thống',
            ],
            [
                'name' => 'Khóa API',
                'description' => 'Cấu hình khóa API cho hệ thống',
            ],
            [
                'name' => 'Email',
                'description' => 'Cấu hình email cho hệ thống',
            ],
            [
                'name' => 'Giới hạn',
                'description' => 'Cấu hình giới hạn cho hệ thống',
            ],
            [
                'name' => 'Thông báo',
                'description' => 'Cấu hình thông báo cho hệ thống',
            ],
            [
                'name' => 'Cấu hình menu',
                'description' => 'Cấu hình menu cho hệ thống',
            ],
        ];

        foreach ($groups as $group) {
            SettingGroup::updateOrCreate([
                'name' => $group['name'],
            ], $group);
        }
    }
}
