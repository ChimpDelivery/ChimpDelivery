<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1, 3) as $id)
        {
            User::factory()->create([
                'email' => "user{$id}@talusstudio.com",
                'name' => "User{$id}",
            ])->syncRoles(['User']);
        }
    }
}
