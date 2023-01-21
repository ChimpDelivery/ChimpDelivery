<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class SuperAdminUserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->createQuietly([
            'name' => 'Super Admin',
            'email' => config('workspaces.superadmin_email'),
        ])->syncRoles([ 'Admin_Super' ]);
    }
}
