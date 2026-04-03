@extends('dashboard-layout.app')

@section('breadcrumb')
<span>Home</span> / <span class="menu-text">Voting Results</span>
@endsection

@section('content')
<div class="row">
    <div class="col-xxl-12">
        @include('components.message-flash')

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title m-0">Result Analytics</h5>
                    <small class="text-muted">Real-time summary from filtered vote data</small>
                </div>

                <hr>

                <form method="GET" action="{{ route('vote.results') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="election_id" class="form-label">Election</label>
                        <select id="election_id" name="election_id" class="form-select">
                            <option value="">All Elections</option>
                            @foreach($elections as $election)
                                <option value="{{ $election->id }}" @selected((string)($filters['election_id'] ?? '') === (string)$election->id)>
                                    {{ $election->name }} ({{ $election->date }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="party_id" class="form-label">Party</label>
                        <select id="party_id" name="party_id" class="form-select">
                            <option value="">All Parties</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->id }}" @selected((string)($filters['party_id'] ?? '') === (string)$party->id)>
                                    {{ $party->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="leader_id" class="form-label">Leader</label>
                        <select id="leader_id" name="leader_id" class="form-select">
                            <option value="">All Leaders</option>
                            @foreach($leaders as $leader)
                                <option value="{{ $leader->id }}" @selected((string)($filters['leader_id'] ?? '') === (string)$leader->id)>
                                    {{ $leader->name }} - {{ $leader->party->name ?? 'Unknown Party' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" id="from_date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                    </div>

                    <div class="col-md-3">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                    </div>

                    <div class="col-md-9 d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('vote.results') }}" class="btn btn-outline-secondary">Reset</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Total Votes</p>
                <h4 class="mb-0">{{ number_format($totalVotes) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Unique Voters</p>
                <h4 class="mb-0">{{ number_format($totalVoters) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Leading Party</p>
                <h6 class="mb-0">{{ $topParty->party_name ?? 'N/A' }}</h6>
                <small class="text-muted">{{ isset($topParty) ? number_format($topParty->total_votes) . ' votes' : 'No votes yet' }}</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-muted mb-1">Top Leader</p>
                <h6 class="mb-0">{{ $topLeader->leader_name ?? 'N/A' }}</h6>
                <small class="text-muted">{{ $activeElectionsCount }} election(s) represented</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Votes by Party</h6>
                <div id="partyVotesChart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Party Vote Share (%)</h6>
                <div id="partyShareChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Election-wise Vote Comparison</h6>
                <div id="electionComparisonChart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h6 class="card-title">Vote Trend by Date</h6>
                <div id="dailyTrendChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xxl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h5 class="card-title">Detailed Results</h5>
                <hr>

                <div id="results-table" class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Election</th>
                                <th>Leader</th>
                                <th>Party</th>
                                <th>Total Votes</th>
                                <th>Vote Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $index => $result)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $result->election_name }}</td>
                                <td>{{ $result->leader_name }}</td>
                                <td>{{ $result->party_name }}</td>
                                <td>{{ number_format($result->total_votes) }}</td>
                                <td style="min-width: 180px;">
                                    <div class="progress mb-1" style="height: 8px;">
                                        <div
                                            class="progress-bar"
                                            role="progressbar"
                                            style="width: {{ min($result->vote_share, 100) }}%;"
                                            aria-valuenow="{{ $result->vote_share }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small>{{ number_format($result->vote_share, 2) }}%</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No votes have been cast for the selected filters.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    (function renderVoteCharts() {
        if (typeof ApexCharts === 'undefined') {
            return;
        }

        const chartColors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6f42c1', '#fd7e14', '#20c997'];
        const noDataText = 'No vote data for selected filters';

        const partyVotesLabels = @json($chartData['partyVotes']['labels'] ?? []);
        const partyVotesSeries = @json($chartData['partyVotes']['series'] ?? []);
        const partyShareLabels = @json($chartData['partyShare']['labels'] ?? []);
        const partyShareSeries = @json($chartData['partyShare']['series'] ?? []);
        const electionLabels = @json($chartData['electionComparison']['labels'] ?? []);
        const electionSeries = @json($chartData['electionComparison']['series'] ?? []);
        const trendLabels = @json($chartData['dailyTrend']['labels'] ?? []);
        const trendSeries = @json($chartData['dailyTrend']['series'] ?? []);

        const partyVotesChart = document.querySelector('#partyVotesChart');
        if (partyVotesChart) {
            new ApexCharts(partyVotesChart, {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                series: [{ name: 'Votes', data: partyVotesSeries }],
                xaxis: { categories: partyVotesLabels, labels: { rotate: -35 } },
                colors: [chartColors[0]],
                dataLabels: { enabled: false },
                noData: { text: noDataText }
            }).render();
        }

        const partyShareChart = document.querySelector('#partyShareChart');
        if (partyShareChart) {
            new ApexCharts(partyShareChart, {
                chart: { type: 'donut', height: 320 },
                series: partyShareSeries,
                labels: partyShareLabels,
                colors: chartColors,
                legend: { position: 'bottom' },
                dataLabels: {
                    enabled: true,
                    formatter: function (value) {
                        return value.toFixed(1) + '%';
                    }
                },
                noData: { text: noDataText }
            }).render();
        }

        const electionComparisonChart = document.querySelector('#electionComparisonChart');
        if (electionComparisonChart) {
            new ApexCharts(electionComparisonChart, {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                series: [{ name: 'Votes', data: electionSeries }],
                xaxis: { categories: electionLabels },
                colors: [chartColors[1]],
                dataLabels: { enabled: false },
                noData: { text: noDataText }
            }).render();
        }

        const dailyTrendChart = document.querySelector('#dailyTrendChart');
        if (dailyTrendChart) {
            new ApexCharts(dailyTrendChart, {
                chart: { type: 'line', height: 320, toolbar: { show: false } },
                series: [{ name: 'Votes', data: trendSeries }],
                xaxis: { categories: trendLabels },
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 4 },
                colors: [chartColors[3]],
                noData: { text: noDataText }
            }).render();
        }
    })();
</script>
@endsection
