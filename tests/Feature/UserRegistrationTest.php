<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    private $registrationUrl = '/auth/v1/register';

    /**
     * Can register an User using name, email and password
     *
     * @return void
     */
    public function test_can_register_an_user_with_password()
    {
        $this->withHeader("Accept", "application/json");

        $user = factory(User::class)->make()->toArray();

        $response = $this->post($this->registrationUrl, $user);
        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
    }

    /**
     * Cannot register with an invalid email
     */
    public function test_cannot_register_an_user_with_invalid_email()
    {
        $this->withHeader("Accept", "application/json");

        $user = factory(User::class)->make()->toArray();

        $user['email'] = "somerandomstring@.com";
        $response = $this->post($this->registrationUrl, $user);
        $response->assertStatus(422);
        $response->assertJson(["message" => "The given data was invalid."]);
    }

    /**
     * Can register from other source like facebook, without submitting a password
     */
    public function test_can_register_an_user_without_password_using_source()
    {
        $this->withHeader("Accept", "application/json");

        $user = factory(User::class)->make()->toArray();
        unset($user['password']);
        $user['source'] = "facebook";
        $user['token'] = $this->faker->text;

        $response = $this->post($this->registrationUrl, $user);

        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
    }

    /**
     * Can register from other source like facebook, without submitting a password
     */
    public function test_cannot_register_an_user_using_invalid_source()
    {
        $this->withHeader("Accept", "application/json");

        $user = factory(User::class)->make()->toArray();
        unset($user['password']);
        $user['source'] = "something";

        $response = $this->post($this->registrationUrl, $user);

        $response->assertStatus(422);
        $response->assertJson(["message" => "The given data was invalid."]);
    }
}
