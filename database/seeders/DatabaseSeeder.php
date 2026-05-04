<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'email_verified_at' => now(),
        ]);

        // Create roles
        $adminRole = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'Administrator']);
        $accountantRole = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'Accountant']);

        // Define permissions
        $permissions = [
            // Tax permissions
            'tax.view',
            'tax.manage',

            // Accounting permissions
            'accounting.view',
            'accounting.manage',

            // Sales permissions
            'sales.view',
            'sales.manage',

            // Purchases permissions
            'purchases.view',
            'purchases.manage',

            // Inventory permissions
            'inventory.view',
            'inventory.manage',

            // Payroll permissions
            'payroll.view',
            'payroll.manage',

            // Settings permissions
            'settings.manage',

            // User management (admin only)
            'users.manage',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions($permissions); // Admin gets all permissions

        $accountantRole->syncPermissions([
            'tax.view',
            'tax.manage',
            'accounting.view',
            'accounting.manage',
            'sales.view',
            'sales.manage',
            'purchases.view',
            'purchases.manage',
            'inventory.view',
            'inventory.manage',
            'payroll.view',
            'payroll.manage',
        ]); // Accountant gets most permissions except user management and settings

        // Create default admin user
        $adminUser = User::updateOrCreate([
            'email' => 'admin@taxsystem.com',
        ], [
            'name' => 'System Administrator',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $adminUser->assignRole('Administrator');

        $this->call(\Database\Seeders\CompanySettingsSeeder::class);
    }

}
