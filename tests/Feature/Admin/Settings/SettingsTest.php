<?php

namespace Tests\Feature\Admin\Settings;

use App\Enums\AccessType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SettingsTest extends TestCase
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

    public function test_get_settings()
    {
        $token = $this->authenticate('admin');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('admin.settings.index'));

        $response->assertStatus(200);

    }

    public function test_create_settings()
    {
        $token = $this->authenticate('super-admin');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('admin.settings.index'), [
            'social_media' => 'facebook'
        ]);

        $response->assertStatus(200);

    }
}
