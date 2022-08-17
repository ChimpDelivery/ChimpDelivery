<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // workspace permissions
        Permission::create([ 'name' => 'view workspace' ]);
        Permission::create([ 'name' => 'update workspace' ]);

        // app permissions
        Permission::create([ 'name' => 'create app' ]);
        Permission::create([ 'name' => 'view app' ]);
        Permission::create([ 'name' => 'update app' ]);
        Permission::create([ 'name' => 'delete app' ]);

        // bundle permissions
        Permission::create([ 'name' => 'create bundle' ]);

        // jenkins permissions
        Permission::create([ 'name' => 'scan jobs']);
        Permission::create([ 'name' => 'build job' ]);
        Permission::create([ 'name' => 'abort job' ]);

        // create roles and assign existing permissions
        $role1 = Role::create( ['name' => 'user' ]);
        $role1->givePermissionTo('create app');
        $role1->givePermissionTo('view app');
        $role1->givePermissionTo('update app');
        $role1->givePermissionTo('scan jobs');

        $role2 = Role::create( ['name' => 'admin' ]);
        $role2->givePermissionTo('view workspace');
        $role2->givePermissionTo('update workspace');

        $role2->givePermissionTo('create app');
        $role2->givePermissionTo('view app');
        $role2->givePermissionTo('update app');
        $role2->givePermissionTo('delete app');

        $role2->givePermissionTo('create bundle');

        $role2->givePermissionTo('scan jobs');
        $role2->givePermissionTo('build job');
        $role2->givePermissionTo('abort job');

        $role3 = Role::create( ['name' => 'Super-Admin' ]);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user1 = \App\Models\User::factory()->create([
            'workspace_id' => 1,
            'name' => 'Example User',
            'email' => 'test@example.com',
        ]);
        $user1->assignRole($role1);

        $user2 = \App\Models\User::factory()->create([
            'workspace_id' => 1,
            'name' => 'Example Admin User',
            'email' => 'admin@example.com',
        ]);
        $user2->assignRole($role2);

        $user3 = \App\Models\User::factory()->create([
            'workspace_id' => 1,
            'name' => 'Example Super-Admin User',
            'email' => 'superadmin@example.com',
        ]);
        $user3->assignRole($role3);
    }
}
