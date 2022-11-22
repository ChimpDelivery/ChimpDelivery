<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Symfony\Component\Console\Output\ConsoleOutput;

class AccessTokenSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function (User $user) {
            $userAccessToken = $user->createApiToken();

            if (app()->environment([ 'local', 'staging' ]))
            {
                $output = new ConsoleOutput();
                $output->writeln("Api Token for User {$user->name}: {$userAccessToken}");
            }
        });
    }
}
