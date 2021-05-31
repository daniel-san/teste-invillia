<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_through_api()
    {
        $user = User::factory()->raw();

        $response = $this->postJson(route('api.auth.register'), $user);

        $response->assertStatus(200)->assertExactJson([
            'message' => 'User registered successfully!'
        ]);
    }

    public function test_a_user_can_login_through_api()
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password')
        ]);

        $response = $this->postJson(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'test-password'
        ]);

        $response->assertStatus(200);
    }
}
