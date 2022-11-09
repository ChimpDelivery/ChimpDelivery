<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Symfony\Component\Console\Output\ConsoleOutput;

class AccessTokenSeeder extends Seeder
{
    public function run()
    {
        $output = new ConsoleOutput();

        User::all()->each(function (User $user) use ($output) {
            $userAccessToken = $user->createApiToken();
            $output->writeln("Api Token for User {$user->name}: {$userAccessToken}");
        });
    }
}
