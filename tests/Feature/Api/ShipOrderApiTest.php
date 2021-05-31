<?php

namespace Tests\Feature\Api;

use App\Http\Resources\ShipOrderResource;
use App\Models\ShipOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShipOrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_must_be_authenticated_to_access()
    {
        $response = $this->json('get', route('api.ship-orders.index'));

        $response->assertStatus(401);
    }

    public function test_it_returns_json_with_shiporders_data()
    {
        $this->seed();

        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $this->actingAs($user);

        $response = $this->withToken($token->accessToken)->withHeaders([
            'Accept' => 'application/json',
        ])->json('get', route('api.ship-orders.index'));

        $collection = ShipOrderResource::collection(
            ShipOrder::with(['person', 'items', 'address'])->get()
        )->toJson();

        $response->assertStatus(200)->assertExactJson(
            json_decode($collection, true)
        );
    }
}
