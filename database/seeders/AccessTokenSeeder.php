<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Output\ConsoleOutput;

use App\Models\User;

class AccessTokenSeeder extends Seeder
{
    public function run()
    {
        $output = new ConsoleOutput();

        if (!app()->isLocal())
        {
            $output->writeln("Access Token seeder is not gonna run! Only local env allowed!");
            return;
        }

        User::all()->each(function (User $user) use ($output) {

            $userAccessToken = $user->createApiToken();

            $output->writeln("Api Token for User {$user->name}: {$userAccessToken}");
        });
    }
}
