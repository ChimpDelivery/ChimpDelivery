<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            WorkspaceSeeder::class,
            WorkspaceInviteCodeSeeder::class,
            PermissionSeeder::class,
            CreateSuperAdminUserSeeder::class,
            CreateWorkspaceAdminSeeder::class,
            CreateWorkspaceUserSeeder::class,
            PackageSeeder::class
        ]);
    }
}
