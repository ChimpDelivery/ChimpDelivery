<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Output\ConsoleOutput;

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $output = new ConsoleOutput();

        if (!app()->isLocal())
        {
            $output->writeln('User seeder is not gonna run! Only local env allowed!');
            return;
        }

        foreach (range(1, 3) as $id)
        {
            User::factory()->createQuietly([
                'email' => "user{$id}@talusstudio.com",
                'name' => "User{$id}",
            ])->syncRoles(['User']);
        }
    }
}
