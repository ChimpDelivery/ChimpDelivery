<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\User\SuperAdminUserSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\User\WorkspaceAdminSeeder;
use Database\Seeders\User\WorkspaceUserSeeder;

use Database\Seeders\Workspace\WorkspaceInviteCodeSeeder;
use Database\Seeders\Workspace\WorkspaceSeeder;

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
