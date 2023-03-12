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

        User::all()->each(function (User $user) use ($output) {

            $userAccessToken = $user->createApiToken('api-key');

            $output->writeln("Api Token for User {$user->name}: {$userAccessToken}");
        });
    }
}
