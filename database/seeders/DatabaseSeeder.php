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
            CreateWorkspaceAdminUserSeeder::class,
            CreateWorkspaceUserSeeder::class,
            PackageSeeder::class
        ]);
    }
}
