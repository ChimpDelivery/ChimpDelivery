<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = collect(config('permission-setup.roles'));
        $roles->map(function ($rolePermissions, $role) {
            $createdRole = Role::create([ 'name' => $role ]);
            $createdRole->syncPermissions($rolePermissions);
        });
    }
}
