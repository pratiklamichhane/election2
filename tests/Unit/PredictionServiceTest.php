<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ElectionPredictionService;
use App\Models\Vote;
use App\Models\Leader;
use App\Models\Party;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_data_returns_correct_status()
    {
        $service = new ElectionPredictionService();
        $endTime = Carbon::now()->addHour();

        $result = $service->predictOutcome($endTime);

        $this->assertEquals('no_data', $result['status']);
        $this->assertEmpty($result['predictions']);
    }

    public function test_completed_election_returns_correct_status()
    {
        $service = new ElectionPredictionService();
        $endTime = Carbon::now()->subMinute(); // Already ended

        $result = $service->predictOutcome($endTime);

        $this->assertEquals('completed', $result['status']);
    }

    public function test_prediction_with_data()
    {
        // Setup initial data
        $election = \App\Models\Election::create(['name' => 'Election 1', 'description' => 'desc', 'date' => Carbon::now()->toDateString(), 'start_date' => Carbon::now()->subDays(1), 'end_date' => Carbon::now()->addDays(1), 'status' => 'active']);

        $partyA = Party::create(['name' => 'Party A', 'description' => 'desc', 'logo' => 'logo']);
        $leaderA = Leader::create(['name' => 'Leader A', 'party_id' => $partyA->id, 'election_id' => $election->id, 'image' => 'img', 'logo' => 'logo', 'description' => 'desc']);

        $partyB = Party::create(['name' => 'Party B', 'description' => 'desc', 'logo' => 'logo']);
        $leaderB = Leader::create(['name' => 'Leader B', 'party_id' => $partyB->id, 'election_id' => $election->id, 'image' => 'img', 'logo' => 'logo', 'description' => 'desc']);

        $user1 = User::create(['name' => 'User1', 'email' => 'user1@test.com', 'password' => 'pass']);
        $user2 = User::create(['name' => 'User2', 'email' => 'user2@test.com', 'password' => 'pass']);
        $user3 = User::create(['name' => 'User3', 'email' => 'user3@test.com', 'password' => 'pass']);

        // Cast votes 10 minutes ago
        $tenMinsAgo = Carbon::now()->subMinutes(10);

        Vote::create(['user_id' => $user1->id, 'party_id' => $partyA->id, 'leader_id' => $leaderA->id, 'created_at' => $tenMinsAgo]);
        Vote::create(['user_id' => $user2->id, 'party_id' => $partyA->id, 'leader_id' => $leaderA->id, 'created_at' => $tenMinsAgo]);
        Vote::create(['user_id' => $user3->id, 'party_id' => $partyB->id, 'leader_id' => $leaderB->id, 'created_at' => $tenMinsAgo]);

        $service = new ElectionPredictionService();
        $endTime = Carbon::now()->addMinutes(10); // Ends in 10 minutes

        $result = $service->predictOutcome($endTime);

        $this->assertContains($result['status'], ['active_prediction', 'early_prediction']);
        $this->assertCount(2, $result['predictions']);

        // Leader A has 2 votes.
        // Leader B has 1 vote.
        // The projected total depends on the actual diff calculation.
        // Just assert the proportions/ranking is correct.

        $this->assertEquals('Leader A', $result['winner']['leader_name']);

        // Predictions are sorted by projected total
        $this->assertEquals('Leader A', $result['predictions'][0]['leader_name']);
        $this->assertEquals('Leader B', $result['predictions'][1]['leader_name']);
        $this->assertGreaterThan($result['predictions'][1]['projected_total'], $result['predictions'][0]['projected_total']);
    }
}
