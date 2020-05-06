<?php

namespace Tests\Feature\Admin;

use App\Enums\AccessType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UsersTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    //Create user and authenticate the user
    protected function authenticate($role)
    {
        Permission::create(['name' => 'list articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'list users']);
        $role = Role::create(['name' => $role]);

        $role->givePermissionTo(Permission::all());

        $user = User::create([
            'first_name' => 'test',
            'last_name' => 'gmail',
            'email' => 'test@gmail.com',
            'password' => bcrypt('password'),
            'access_type' => AccessType::ADMIN,

        ]);
        $user->assignRole(Role::find(1)->name);

        return $user->createToken('android')->plainTextToken;
    }

    public function test_get_users()
    {
        $token = $this->authenticate('admin');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('admin.users.show'));

        $response->assertStatus(200);
    }

    public function test_create_a_user()
    {
        $token = $this->authenticate('super-admin');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('admin.users.create'), [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => 'password',
            'role' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_update_a_user()
    {
        $user = factory('App\User')->create();
        $token = $this->authenticate('super-admin');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('admin.users.update',['user_id'=>$user->id]), [
            'first_name' => 'updated',
            'last_name' => $this->faker->lastName,
            'email' => $user->email,
            'role' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertSee('admin updated');
    }
}
