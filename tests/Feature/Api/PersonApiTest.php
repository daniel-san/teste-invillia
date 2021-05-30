<?php

namespace Tests\Feature\Api;

use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_must_be_authenticated_to_access()
    {
        $response = $this->json('get', route('api.people.index'));

        $response->assertStatus(401);
    }

    public function test_it_returns_json_with_people_data()
    {
        $this->seed();

        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $this->actingAs($user);

        $response = $this->withToken($token->accessToken)->withHeaders([
            'Accept' => 'application/json',
        ])->json('get', route('api.people.index'));

        $response->assertStatus(200)->assertExactJson(
            PersonResource::collection(
                Person::with(['phones'])->get()
            )->toArray(request())
        );
    }
}
