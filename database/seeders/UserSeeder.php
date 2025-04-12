<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
        ]);

        // users
        for ($i = 0; $i < 10; $i++) {
            User::updateOrCreate([
                'email' => 'user' . $i . '@gmail.com',
            ], [
                'name' => 'User ' . $i,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
