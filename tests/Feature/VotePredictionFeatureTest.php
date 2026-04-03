<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kyc;

class VotePredictionFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_predict_route_returns_successful_response(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        Kyc::create([
            'user_id' => $user->id,
            'nagrita_number' => '54017500023',
            'verification_status' => 'verified',
            'verification_score' => 95,
        ]);

        $response = $this->actingAs($user)->get(route('vote.predict'));

        $response->assertStatus(200);
        $response->assertViewIs('vote.predict');
        $response->assertViewHas('predictionData');
    }
}
