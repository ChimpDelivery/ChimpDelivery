<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'email' => 'user1@example.com',
            'name' => 'User1',
        ])->syncRoles(['User']);

        User::factory()->create([
            'email' => 'user2@example.com',
            'name' => 'User2',
        ])->syncRoles(['User']);

        User::factory()->create([
            'email' => 'user3@example.com',
            'name' => 'User3',
        ])->syncRoles(['User']);
    }
}
