<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'access-admin',
            'manage-categories',
            'manage-products',
            'manage-orders',
            'manage-settings',
            'manage-design',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $shopPermissions = [
            'access-admin',
            'manage-categories',
            'manage-products',
            'manage-orders',
        ];

        $fullPermissions = array_merge($shopPermissions, [
            'manage-settings',
            'manage-design',
        ]);

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions($fullPermissions);

        $legacyAdmin = Role::firstOrCreate(['name' => 'admin']);
        $legacyAdmin->syncPermissions($fullPermissions);

        $storeOwner = Role::firstOrCreate(['name' => 'store_owner']);
        $storeOwner->syncPermissions($shopPermissions);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasAnyRole(['super_admin', 'admin'])) {
            $admin->assignRole('super_admin');
        }

        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $superAdminUser->hasRole('super_admin')) {
            $superAdminUser->assignRole('super_admin');
        }

        User::role('admin')->each(function (User $user) {
            if (! $user->hasRole('super_admin')) {
                $user->assignRole('super_admin');
            }
        });

        $this->command?->info('Roles ready: super_admin, store_owner, admin (legacy).');
        $this->command?->info('Email: admin@example.com');
        $this->command?->info('Email: superadmin@test.com');
    }
}