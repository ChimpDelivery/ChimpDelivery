<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([ 'name' => 'User' ]);
        Role::create([ 'name' => 'Admin_Workspace' ]);
        Role::create([ 'name' => 'Admin_Super' ]);
    }
}
