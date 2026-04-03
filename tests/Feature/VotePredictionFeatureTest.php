<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class VotePredictionFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_predict_route_returns_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('vote.predict'));

        $response->assertStatus(200);
        $response->assertViewIs('vote.predict');
        $response->assertViewHas('predictionData');
    }
}
