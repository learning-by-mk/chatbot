<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo role 'admin' tồn tại
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Tạo 1 tài khoản admin
        $admin = User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'job' => 'System Administrator',
            'phone' => '0123456789',
            'bio' => 'Quản trị viên hệ thống',
            'hobbies' => 'Coding, Reading, Gaming',
            'status' => 'active',
        ]);

        // Gán quyền admin
        $admin->assignRole('admin');

        // Tạo 9 người dùng thường
        for ($i = 1; $i <= 9; $i++) {
            $user = User::updateOrCreate([
                'email' => 'user' . $i . '@example.com',
            ], [
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'job' => 'Nghề nghiệp ' . $i,
                'phone' => '098765432' . $i,
                'bio' => 'Đây là thông tin giới thiệu của người dùng ' . $i,
                'hobbies' => 'Sở thích ' . $i,
                'status' => 'active',
            ]);
            $user->assignRole('user');
        }
    }
}
