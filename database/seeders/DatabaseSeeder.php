<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\User\SuperAdminUserSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\User\WorkspaceAdminSeeder;
use Database\Seeders\User\WorkspaceUserSeeder;

use Database\Seeders\Workspace\WorkspaceInviteCodeSeeder;
use Database\Seeders\Workspace\WorkspaceSeeder;

use Database\Seeders\Settings\AppleSettingSeeder;
use Database\Seeders\Settings\AppStoreConnectSettingSeeder;
use Database\Seeders\Settings\GithubSettingSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([

            // seed workspace related
            WorkspaceSeeder::class,
            WorkspaceInviteCodeSeeder::class,
            AppleSettingSeeder::class,
            AppStoreConnectSettingSeeder::class,
            GithubSettingSeeder::class,

            // seed roles
            PermissionSeeder::class,
            RoleSeeder::class,

            // seed users
            UserSeeder::class,
            WorkspaceUserSeeder::class,
            WorkspaceAdminSeeder::class,
            SuperAdminUserSeeder::class,

            // seed user tokens
            AccessTokenSeeder::class,

            // seed internal
            PackageSeeder::class,
        ]);
    }
}
