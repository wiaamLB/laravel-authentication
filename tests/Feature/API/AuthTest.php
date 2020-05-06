<?php

namespace Tests\Feature\API;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     * Test registration
     */
    public function testRegister()
    {
        //User's data
        $data = [
            'email' => 'test@gmail.com',
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'personal_mobile_number' => $this->faker->phoneNumber,
            'more_info' => '',
        ];
        //Send post request
        $response = $this->json('POST', route('api.register'), $data);
        //Assert it was successful
        $response->assertStatus(200);

    }

    /**
     * @test
     * Test login
     */
    public function testLogin()
    {
        //Create user
        User::create([
            'first_name'=>'test',
            'last_name'=>'gmail',
            'email'=>'test@gmail.com',
            'password' => bcrypt('secret1234')
        ]);
        //attempt login
        $response = $this->json('POST',route('api.authenticate'),[
            'device_name' => 'android',
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]);
        //Assert it was successful and a token was received
        $response->assertStatus(200);
        $this->assertArrayHasKey('token',$response->json());
        //Delete the user
        User::where('email','test@gmail.com')->delete();
    }
}
