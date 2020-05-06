<?php

namespace Tests\Feature\Admin\Pages;

use App\Enums\AccessType;
use App\Page;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    //Create user and authenticate the user
    protected function authenticate()
    {
        Permission::create(['name' => 'list articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'list users']);
        $role = Role::create(['name' => 'admin']);

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


    public function test_get_page()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('admin.pages.index'));

        $response->assertStatus(200);
    }

    public function test_create_page()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('admin.pages.store'), [
            'title' => $this->faker->title,
            'alias' => $this->faker->word,
            'active' => $this->faker->boolean,
            'page_title' => $this->faker->title,
            'description' => $this->faker->sentence,
            'content' => $this->faker->sentence,
        ]);
        //Assert it was successful and a token was received
        $response->assertStatus(200);

    }

    public function test_view_page()
    {
        $token = $this->authenticate();

        $page = factory('App\Page')->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('admin.pages.show',['id'=>$page->id]));
        $response->assertStatus(200);
        $response->assertSee($page->id);
        $response->assertSee($page->title);
    }

    public function test_delete_page()
    {
        $token = $this->authenticate();

        $page = factory('App\Page')->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', route('admin.pages.delete',['id'=>$page->id]));
        $response->assertStatus(200);
    }

}
