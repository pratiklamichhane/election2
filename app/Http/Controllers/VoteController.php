<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Party;
use App\Models\Leader;
use App\Models\Election;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\ElectionPredictionService;

class VoteController extends Controller
{
    public function index()
    {
        $parties = Party::all();
        $leaders = Leader::all();
        $electionEndTime = now()->addMinutes(10); // Set this dynamically as per your requirements
        // Get the authenticated user's ID
        $userId = auth()->id();

         // Check if the user has already voted
         $hasVoted = Vote::where('user_id', $userId)->exists();
        return view('vote.index', compact('parties', 'leaders', 'hasVoted', 'electionEndTime'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request to ensure proper data
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'leader_id' => 'required|exists:leaders,id',
        ]);

        // Get the authenticated user's ID
        $userId = auth()->id();

        // Get the party ID from the request
        $partyId = $request->party_id;

        // Check if the user has already voted for the same party
        $existingVote = Vote::where('user_id', $userId)
                            ->where('party_id', $partyId)
                            ->first();

        if ($existingVote) {
            // Redirect back with an error message if the user already voted
            return redirect()->route('voting.index')->with('error', 'You have already voted for this party.');
        }

        // Create the vote record
        Vote::create([
            'user_id' => $userId,
            'party_id' => $partyId,
            'leader_id' => $request->leader_id,
        ]);

        // Redirect back with a success message
        return redirect()->route('voting.index')->with('success', 'Vote cast successfully.');
    }

    //vote results
    public function results(Request $request)
    {
        $filters = $request->validate([
            'election_id' => ['nullable', 'integer', 'exists:elections,id'],
            'party_id' => ['nullable', 'integer', 'exists:parties,id'],
            'leader_id' => ['nullable', 'integer', 'exists:leaders,id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);

        $elections = Election::orderByDesc('date')->get(['id', 'name', 'date']);
        $parties = Party::orderBy('name')->get(['id', 'name']);
        $leaders = Leader::query()
            ->with(['party:id,name', 'election:id,name'])
            ->when($filters['election_id'] ?? null, function ($query, $electionId) {
                return $query->where('election_id', $electionId);
            })
            ->when($filters['party_id'] ?? null, function ($query, $partyId) {
                return $query->where('party_id', $partyId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'party_id', 'election_id']);

        $baseQuery = Vote::query()
            ->join('leaders', 'votes.leader_id', '=', 'leaders.id')
            ->join('parties', 'votes.party_id', '=', 'parties.id')
            ->join('elections', 'leaders.election_id', '=', 'elections.id')
            ->when($filters['election_id'] ?? null, function ($query, $electionId) {
                return $query->where('leaders.election_id', $electionId);
            })
            ->when($filters['party_id'] ?? null, function ($query, $partyId) {
                return $query->where('votes.party_id', $partyId);
            })
            ->when($filters['leader_id'] ?? null, function ($query, $leaderId) {
                return $query->where('votes.leader_id', $leaderId);
            })
            ->when($filters['from_date'] ?? null, function ($query, $fromDate) {
                return $query->whereDate('votes.created_at', '>=', $fromDate);
            })
            ->when($filters['to_date'] ?? null, function ($query, $toDate) {
                return $query->whereDate('votes.created_at', '<=', $toDate);
            });

        $totalVotes = (clone $baseQuery)->count('votes.id');
        $totalVoters = (clone $baseQuery)->distinct()->count('votes.user_id');

        $results = (clone $baseQuery)
            ->select([
                'leaders.id as leader_id',
                'leaders.name as leader_name',
                'parties.id as party_id',
                'parties.name as party_name',
                'elections.id as election_id',
                'elections.name as election_name',
                DB::raw('COUNT(votes.id) as total_votes'),
            ])
            ->groupBy(
                'leaders.id',
                'leaders.name',
                'parties.id',
                'parties.name',
                'elections.id',
                'elections.name'
            )
            ->orderByDesc('total_votes')
            ->get()
            ->map(function ($result) use ($totalVotes) {
                $result->vote_share = $totalVotes > 0
                    ? round(($result->total_votes / $totalVotes) * 100, 2)
                    : 0.0;

                return $result;
            });

        $partyResults = (clone $baseQuery)
            ->select([
                'parties.id as party_id',
                'parties.name as party_name',
                DB::raw('COUNT(votes.id) as total_votes'),
            ])
            ->groupBy('parties.id', 'parties.name')
            ->orderByDesc('total_votes')
            ->get();

        $electionResults = (clone $baseQuery)
            ->select([
                'elections.id as election_id',
                'elections.name as election_name',
                'elections.date as election_date',
                DB::raw('COUNT(votes.id) as total_votes'),
            ])
            ->groupBy('elections.id', 'elections.name', 'elections.date')
            ->orderByDesc('total_votes')
            ->get();

        $dailyTrend = (clone $baseQuery)
            ->select([
                DB::raw('DATE(votes.created_at) as vote_date'),
                DB::raw('COUNT(votes.id) as total_votes'),
            ])
            ->groupBy(DB::raw('DATE(votes.created_at)'))
            ->orderBy('vote_date')
            ->get();

        $topLeader = $results->first();
        $topParty = $partyResults->first();
        $activeElectionsCount = $electionResults->count();

        $chartData = [
            'partyVotes' => [
                'labels' => $partyResults->pluck('party_name')->values(),
                'series' => $partyResults->pluck('total_votes')->map(fn ($votes) => (int) $votes)->values(),
            ],
            'partyShare' => [
                'labels' => $partyResults->pluck('party_name')->values(),
                'series' => $partyResults->map(function ($row) use ($totalVotes) {
                    if ($totalVotes <= 0) {
                        return 0;
                    }

                    return round(($row->total_votes / $totalVotes) * 100, 2);
                })->values(),
            ],
            'dailyTrend' => [
                'labels' => $dailyTrend->pluck('vote_date')->values(),
                'series' => $dailyTrend->pluck('total_votes')->map(fn ($votes) => (int) $votes)->values(),
            ],
            'electionComparison' => [
                'labels' => $electionResults->pluck('election_name')->values(),
                'series' => $electionResults->pluck('total_votes')->map(fn ($votes) => (int) $votes)->values(),
            ],
        ];

        return view('vote.results', compact(
            'results',
            'elections',
            'parties',
            'leaders',
            'filters',
            'totalVotes',
            'totalVoters',
            'topLeader',
            'topParty',
            'activeElectionsCount',
            'chartData'
        ));
    }

    public function predict(ElectionPredictionService $predictionService)
    {
        // Assuming the election ends 1 hour from the start for demonstration,
        // or getting it from a configuration/database if available.
        // We will just use the hardcoded 10 minutes logic from the index method.
        // If there's an actual Election model, it should be passed here.
        // Here we just mock the end time to be some time in the future for prediction purposes.
        $electionEndTime = Carbon::now()->addHours(1); // Adjust as needed for the project

        $predictionData = $predictionService->predictOutcome($electionEndTime);

        return view('vote.predict', compact('predictionData'));
    }

}
