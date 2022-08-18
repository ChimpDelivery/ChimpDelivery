<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'workspace_id' => 1,
            'email' => 'user1@example.com',
            'name' => 'User',
            'password' => bcrypt('123456')
        ]);

        $user->syncRoles([ 'User' ]);
    }
}
