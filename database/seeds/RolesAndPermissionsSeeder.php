<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create permissions
        Permission::create(['name' => 'list articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);

        Permission::create(['name' => 'list users']);


        // this can be done as separate statements
        /**
         * @var Role $role
         */
        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo(['list articles', 'edit articles']);

        // this can be done as separate statements
        //Not Used in basma
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo(['list articles', 'publish articles']);

        // or may be done by chaining
        $role = Role::create(['name' => 'moderator'])
            ->givePermissionTo(['list articles', 'publish articles', 'edit articles']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());


        Permission::create(['name' => 'create users']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
