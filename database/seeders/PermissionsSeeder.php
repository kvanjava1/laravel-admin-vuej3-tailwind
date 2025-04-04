<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cms\Mysql\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // dont uncomment , this will remove all permission before seeding
        // Permission::query()->delete();

        $permissionGroups = [
            'dashboard' => ['dashboard.view'],
            'users' => ['users.role.view', 'users.role.add'],
        ];

        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'api',
                ]);
            }
        }

        $superAdminRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'api',
        ]);

        $superAdminRole->syncPermissions(Permission::where('guard_name', 'api')->get());

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'), // Ensure password is hashed
            ]
        );

        $admin->assignRole(Role::findByName('superadmin', 'api'));
    }
}
