<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class SuperAdminUserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ])->syncRoles([ 'Admin_Super' ]);
    }
}
