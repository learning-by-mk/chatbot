<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Trang chủ',
                'slug' => 'trang-chu',
                'href' => '/',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Danh mục',
                'slug' => 'danh-muc',
                'href' => '/categories',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Tài liệu',
                'slug' => 'tai-lieu',
                'href' => '/documents',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Giới thiệu',
                'slug' => 'gioi-thieu',
                'href' => '/about',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Liên hệ',
                'slug' => 'lien-he',
                'href' => '/contact',
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(['slug' => $menu['slug']], $menu);
        }
    }
}
