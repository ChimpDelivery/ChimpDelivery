<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class SuperAdminUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'workspace_id' => 1,
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('123456')
        ]);

        $user->syncRoles([ 'Admin_Super' ]);
    }
}
