<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            DocumentSeeder::class,
            DocumentCategorySeeder::class,
            CommentSeeder::class,
            FavoriteSeeder::class,
            DownloadSeeder::class,
            SettingGroupSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
