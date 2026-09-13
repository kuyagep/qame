<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Core Permissions Set
        $permissions = [
            // Administration Framework Modules
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.manage',
            'permissions.view',

            // Phase 3 Master Data Module Elements
            'schools.view',
            'schools.create',
            'schools.edit',
            'schools.delete',
            'districts.view',
            'districts.create',
            'districts.edit',
            'districts.delete',
            'offices.view',
            'offices.create',
            'offices.edit',
            'offices.delete',

            // Phase 4 Auxiliary Extensions Shared Blocks
            'reports.view',
            'settings.manage',
            'profile.manage',
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create Roles and Assign Permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin']);
        // Super Admin implicitly gets all permissions via a Gate loop setup, but we assign here explicitly too

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo([
            'schools.view',
            'schools.create',
            'schools.edit',
            'districts.view',
            'offices.view',
            // 'reports.view'
        ]);

        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffRole->givePermissionTo([
            'profile.manage',

        ]);

        // 3. Create Primary Root Administrative Users
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'username' => 'superadmin',
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $superadmin->assignRole($superAdminRole);

        $admin = User::firstOrCreate(
            ['email' => 'division@gmail.com'],
            [
                'username' => 'admin',
                'name' => 'Division Account',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $admin->assignRole($adminRole);
        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'username' => 'staff',
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $staff->assignRole($staffRole);
    }
}
