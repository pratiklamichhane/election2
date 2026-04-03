@extends('dashboard-layout.app')

@section('breadcrumb')
<span>Home</span> / <span class="menu-text">Dashboard</span>
@endsection

@section('content')
@include('components.message-flash')

@if(auth()->user()->role === 'admin')
<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Registered Users</p>
                <h4 class="mb-0">{{ number_format($adminStats['total_users'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Active Users</p>
                <h4 class="mb-0">{{ number_format($adminStats['active_users'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Elections</p>
                <h4 class="mb-0">{{ number_format($adminStats['total_elections'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Votes Cast</p>
                <h4 class="mb-0">{{ number_format($adminStats['total_votes'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Parties</p>
                <h4 class="mb-0">{{ number_format($adminStats['total_parties'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Leaders</p>
                <h4 class="mb-0">{{ number_format($adminStats['total_leaders'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Pending KYC</p>
                <h4 class="mb-0">{{ number_format($adminStats['pending_kyc'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Verified KYC</p>
                <h4 class="mb-0">{{ number_format($adminStats['verified_kyc'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xxl-12">
        <div class="card shadow mb-4">
            <div class="card-body d-flex flex-wrap gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-primary">Manage Users</a>
                <a href="{{ route('elections.index') }}" class="btn btn-outline-primary">Manage Elections</a>
                <a href="{{ route('partys.index') }}" class="btn btn-outline-primary">Manage Parties</a>
                <a href="{{ route('leaders.index') }}" class="btn btn-outline-primary">Manage Leaders</a>
                <a href="{{ route('vote.results') }}" class="btn btn-outline-success">View Results</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Votes in Last 7 Days</h6>
                <div id="adminVotesTrendChart"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Top Parties by Votes</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th class="text-end">Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topParties as $party)
                            <tr>
                                <td>{{ $party->party_name }}</td>
                                <td class="text-end">{{ number_format($party->total_votes) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No vote records yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xxl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Recently Registered Users</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Joined On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $recentUser)
                            <tr>
                                <td>{{ $recentUser->name }}</td>
                                <td>{{ $recentUser->email }}</td>
                                <td>
                                    @if($recentUser->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $recentUser->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Account Status</p>
                @if($userStats['is_active'] ?? false)
                    <h6 class="mb-0 text-success">Active</h6>
                @else
                    <h6 class="mb-0 text-warning">Pending Verification</h6>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">KYC Status</p>
                @php($kycStatus = $userStats['kyc_status'] ?? 'not_submitted')
                <h6 class="mb-0 text-capitalize">{{ str_replace('_', ' ', $kycStatus) }}</h6>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">My Votes</p>
                <h4 class="mb-0">{{ number_format($userStats['my_votes'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Total Elections</p>
                <h4 class="mb-0">{{ number_format($userStats['total_elections'] ?? 0) }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">My Voting Readiness</h6>
                <p class="mb-2">
                    @if($userStats['can_vote'] ?? false)
                        <span class="badge bg-success">Eligible to vote</span>
                    @else
                        <span class="badge bg-warning text-dark">Complete verification to vote</span>
                    @endif
                </p>
                <p class="text-muted mb-0">
                    Active account and verified KYC are required before voting.
                </p>
                @if($kyc && $kyc->verified_at)
                    <p class="text-muted mt-2 mb-0">Verified at: {{ $kyc->verified_at->format('Y-m-d H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Election Catalog</h6>
                <p class="mb-2">Parties: <strong>{{ number_format($userStats['available_parties'] ?? 0) }}</strong></p>
                <p class="mb-0">Leaders: <strong>{{ number_format($userStats['available_leaders'] ?? 0) }}</strong></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-body d-flex flex-wrap gap-2">
                @if($userStats['can_vote'] ?? false)
                    <a href="{{ route('vote.index') }}" class="btn btn-primary">Go to Voting</a>
                    <a href="{{ route('vote.results') }}" class="btn btn-outline-success">View Results</a>
                    <a href="{{ route('vote.predict') }}" class="btn btn-outline-info">Prediction</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary disabled" aria-disabled="true">Voting Locked</a>
                    <a href="{{ route('vote.results') }}" class="btn btn-outline-success">View Results</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Last Vote Activity</h6>
                @if($lastVote)
                    <p class="mb-1">Party: <strong>{{ $lastVote->party->name ?? 'N/A' }}</strong></p>
                    <p class="mb-1">Leader: <strong>{{ $lastVote->leader->name ?? 'N/A' }}</strong></p>
                    <p class="mb-0 text-muted">At: {{ $lastVote->created_at?->format('Y-m-d H:i') }}</p>
                @else
                    <p class="text-muted mb-0">No votes submitted yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xxl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Top Parties (Current Trend)</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th class="text-end">Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topParties as $party)
                            <tr>
                                <td>{{ $party->party_name }}</td>
                                <td class="text-end">{{ number_format($party->total_votes) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No vote records available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
@if(auth()->user()->role === 'admin')
<script>
    (function renderAdminVotesTrend() {
        if (typeof ApexCharts === 'undefined') {
            return;
        }

        const labels = @json($dailyVotesChart['labels'] ?? []);
        const data = @json($dailyVotesChart['series'] ?? []);
        const chartTarget = document.querySelector('#adminVotesTrendChart');

        if (!chartTarget) {
            return;
        }

        new ApexCharts(chartTarget, {
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false }
            },
            series: [{
                name: 'Votes',
                data: data
            }],
            xaxis: {
                categories: labels
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#0d6efd'],
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.5,
                    opacityTo: 0.08
                }
            },
            noData: {
                text: 'No vote activity for this period'
            }
        }).render();
    })();
</script>
@endif
@endsection
