<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard_name = config('auth.defaults.guard');
        // create permission for each combination of table.level
        collect([ // tables
            'users',
            'roles',
            'settings',
        ])
            ->crossJoin([ // levels
                'list',
                'view',
                'update',
                'delete',
                'create',
            ])
            ->each(
                fn(array $item) => Permission::firstOrCreate([
                    'name' => implode('.', $item),
                    'guard_name' => $guard_name,
                ])->save()
            );


        // Create admin role and assign all permissions to
        $all_permissions = Permission::where('guard_name', $guard_name)->get();
        $admin_role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guard_name,
        ]);
        foreach ($all_permissions as $permission) {
            $admin_role->givePermissionTo($permission);
        }

        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
