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

        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->syncRoles(['super_admin']);

        $ownerUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Store Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $ownerUser->syncRoles(['store_owner']);

        User::role('admin')->each(function (User $user) {
            if (! $user->hasRole('super_admin')) {
                $user->assignRole('super_admin');
            }
        });

        $this->command?->info('Roles ready: super_admin, store_owner, admin (legacy).');
        $this->command?->info('Super Admin: superadmin@test.com / password');
        $this->command?->info('Store Owner: admin@example.com / password');
    }
}