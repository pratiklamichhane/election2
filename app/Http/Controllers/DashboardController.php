<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Kyc;
use App\Models\Leader;
use App\Models\Party;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $adminStats = null;
        $recentUsers = collect();
        $topParties = collect();
        $dailyVotesChart = ['labels' => [], 'series' => []];

        $userStats = null;
        $kyc = $user->kyc;
        $lastVote = null;

        if ($user->role === 'admin') {
            $adminStats = [
                'total_users' => User::where('role', 'user')->count(),
                'active_users' => User::where('role', 'user')->where('is_active', true)->count(),
                'total_elections' => Election::count(),
                'total_parties' => Party::count(),
                'total_leaders' => Leader::count(),
                'total_votes' => Vote::count(),
                'pending_kyc' => Kyc::where('verification_status', 'pending')->count(),
                'verified_kyc' => Kyc::where('verification_status', 'verified')->count(),
            ];

            $recentUsers = User::query()
                ->where('role', 'user')
                ->latest()
                ->take(6)
                ->get(['id', 'name', 'email', 'created_at', 'is_active']);

            $topParties = Vote::query()
                ->join('parties', 'votes.party_id', '=', 'parties.id')
                ->select('parties.name as party_name', DB::raw('COUNT(votes.id) as total_votes'))
                ->groupBy('parties.name')
                ->orderByDesc('total_votes')
                ->take(5)
                ->get();

            $dailyVotes = Vote::query()
                ->select(DB::raw('DATE(created_at) as vote_date'), DB::raw('COUNT(id) as total_votes'))
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('vote_date')
                ->get()
                ->keyBy('vote_date');

            $labels = [];
            $series = [];

            for ($i = 6; $i >= 0; $i--) {
                $day = now()->subDays($i)->format('Y-m-d');
                $labels[] = $day;
                $series[] = (int) optional($dailyVotes->get($day))->total_votes;
            }

            $dailyVotesChart = [
                'labels' => $labels,
                'series' => $series,
            ];
        } else {
            $votesQuery = Vote::query()->where('user_id', $user->id);
            $lastVote = (clone $votesQuery)
                ->with(['party:id,name', 'leader:id,name'])
                ->latest()
                ->first();

            $canVote = $user->is_active && $kyc && $kyc->verification_status === 'verified';

            $userStats = [
                'kyc_status' => $kyc?->verification_status ?? 'not_submitted',
                'can_vote' => $canVote,
                'is_active' => (bool) $user->is_active,
                'my_votes' => (clone $votesQuery)->count(),
                'has_voted' => (clone $votesQuery)->exists(),
                'total_elections' => Election::count(),
                'available_parties' => Party::count(),
                'available_leaders' => Leader::count(),
            ];

            $topParties = Vote::query()
                ->join('parties', 'votes.party_id', '=', 'parties.id')
                ->select('parties.name as party_name', DB::raw('COUNT(votes.id) as total_votes'))
                ->groupBy('parties.name')
                ->orderByDesc('total_votes')
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'adminStats',
            'recentUsers',
            'topParties',
            'dailyVotesChart',
            'userStats',
            'lastVote',
            'kyc'
        ));
    }
}
