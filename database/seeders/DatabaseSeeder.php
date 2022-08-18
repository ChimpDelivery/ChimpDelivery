<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            WorkspaceSeeder::class,
            WorkspaceInviteCodeSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            WorkspaceUserSeeder::class,
            WorkspaceAdminSeeder::class,
            SuperAdminUserSeeder::class,
            PackageSeeder::class
        ]);
    }
}
