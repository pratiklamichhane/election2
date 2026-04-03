<?php

namespace App\Services;

use App\Models\Vote;
use App\Models\Leader;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ElectionPredictionService
{
    /**
     * Predicts the outcome of an election based on current voting velocity.
     *
     * @param Carbon $electionEndTime The time the election is scheduled to end.
     * @return array The prediction details.
     */
    public function predictOutcome(Carbon $electionEndTime): array
    {
        $now = Carbon::now();

        // If election is over, just return actual results as prediction
        if ($now->greaterThanOrEqualTo($electionEndTime)) {
            return [
                'status' => 'completed',
                'predictions' => $this->getCurrentResults(),
                'winner' => $this->getActualWinner()
            ];
        }

        $firstVote = Vote::orderBy('created_at', 'asc')->first();

        // If no votes have been cast yet, we cannot predict
        if (!$firstVote) {
             return [
                'status' => 'no_data',
                'message' => 'Not enough data to make a prediction.',
                'predictions' => [],
                'winner' => null
            ];
        }

        $electionStartTime = $firstVote->created_at;
        $timeElapsedMinutes = $electionStartTime->diffInMinutes($now);
        $timeRemainingMinutes = $now->diffInMinutes($electionEndTime);

        // If time elapsed is too short, predictions might be highly inaccurate
        // but for a college project we can proceed anyway, maybe with a warning.
        $status = $timeElapsedMinutes < 5 ? 'early_prediction' : 'active_prediction';

        // Avoid division by zero if less than a minute has passed
        $timeElapsedMinutes = max($timeElapsedMinutes, 1);

        // Get current vote counts grouped by leader and party
        $currentResults = Vote::select('leaders.id as leader_id', 'leaders.name as leader_name', 'parties.name as party_name', DB::raw('COUNT(votes.id) as total_votes'))
            ->join('leaders', 'votes.leader_id', '=', 'leaders.id')
            ->join('parties', 'votes.party_id', '=', 'parties.id')
            ->groupBy('leaders.id', 'leaders.name', 'parties.name')
            ->get();

        $predictions = [];
        $totalProjectedVotes = 0;

        foreach ($currentResults as $result) {
            $currentVotes = $result->total_votes;

            // Calculate velocity (votes per minute)
            $velocity = $currentVotes / $timeElapsedMinutes;

            // Project future votes based on velocity and remaining time
            $projectedAdditionalVotes = $velocity * $timeRemainingMinutes;

            // Calculate final projected votes
            $projectedFinalVotes = $currentVotes + $projectedAdditionalVotes;

            $totalProjectedVotes += $projectedFinalVotes;

            $predictions[] = [
                'leader_name' => $result->leader_name,
                'party_name' => $result->party_name,
                'current_votes' => $currentVotes,
                'velocity_per_minute' => round($velocity, 2),
                'projected_additional' => round($projectedAdditionalVotes),
                'projected_total' => round($projectedFinalVotes),
            ];
        }

        // Sort predictions by projected total (highest first)
        usort($predictions, function ($a, $b) {
            return $b['projected_total'] <=> $a['projected_total'];
        });

        // Calculate win probability (simple model based on vote share projection)
        foreach ($predictions as &$prediction) {
            $prediction['win_probability'] = $totalProjectedVotes > 0
                ? round(($prediction['projected_total'] / $totalProjectedVotes) * 100, 2)
                : 0;
        }

        $predictedWinner = count($predictions) > 0 ? $predictions[0] : null;

        return [
            'status' => $status,
            'predictions' => $predictions,
            'winner' => $predictedWinner,
            'time_elapsed_minutes' => $timeElapsedMinutes,
            'time_remaining_minutes' => $timeRemainingMinutes
        ];
    }

    private function getCurrentResults(): array
    {
        $results = Vote::select('leaders.name as leader_name', 'parties.name as party_name', DB::raw('COUNT(votes.id) as current_votes'))
            ->join('leaders', 'votes.leader_id', '=', 'leaders.id')
            ->join('parties', 'votes.party_id', '=', 'parties.id')
            ->groupBy('leaders.name', 'parties.name')
            ->orderBy('current_votes', 'DESC')
            ->get()
            ->toArray();

        return array_map(function($item) {
            $item['projected_total'] = $item['current_votes']; // For completed elections
            $item['win_probability'] = 0; // Not needed if completed, or could calculate actual final percentage
            return $item;
        }, $results);
    }

    private function getActualWinner()
    {
         $winner = Vote::select('leaders.name as leader_name', 'parties.name as party_name', DB::raw('COUNT(votes.id) as current_votes'))
            ->join('leaders', 'votes.leader_id', '=', 'leaders.id')
            ->join('parties', 'votes.party_id', '=', 'parties.id')
            ->groupBy('leaders.name', 'parties.name')
            ->orderBy('current_votes', 'DESC')
            ->first();

        return $winner ? $winner->toArray() : null;
    }
}
