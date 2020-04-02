<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    private $registrationUrl = '/auth/v1/register';
    private $loginUrl = 'auth/v1/login';
    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader("Accept", "application/json");

        $this->user = factory(User::class)->make();
        $response = $this->post($this->registrationUrl, $this->user->toArray());
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);
        $this->token = $data['access_token'];


    }

    public function test_can_login_using_credentials()
    {
        $this->withHeader("Accept", "application/json");

        $response = $this->post($this->loginUrl, [
            'email' => $this->user->email,
            'password' => $this->user->password
        ]);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

    }

    public function test_can_login_from_other_credentials()
    {
        $this->withHeader("Accept", "application/json");
        $user = factory(User::class)->make();
        $user = $user->toArray();
        $user['source'] = 'google';
        $user['token'] = $this->faker->text;
        unset($user['password']);

        $response = $this->post($this->registrationUrl, $user);
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);
        $token = $data['access_token'];


        $response = $this->post($this->loginUrl, [
            'name' => $user['name'],
            'email' => $user['email'],
            'source' => $user['source'],
            'token' => $user['token']
        ]);

        dd($response->getContent(), $user);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);


    }
}
