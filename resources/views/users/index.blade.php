@extends('dashboard-layout.app')

@section('breadcrumb')
<span>Home</span> / <span class="menu-text">Services & Packages</span>
@endsection

@section('content')

<div class="row">
    <div class="col-xxl-12">
        @include('components.message-flash')

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Users and Requests</h5>
                </div>

                <!-- Search Bar -->
                <form method="GET" action="{{ route('users.index') }}" class="mb-3 d-flex mt-3">
                  <input 
                    type="text" 
                    id="search-bar" 
                    name="search" 
                    class="form-control w-25" 
                    placeholder="Search users..." 
                    value="{{ request('search') }}">
                </form>
                <hr>

                <div class="table-responsive">
                    <table class="table align-middle table-hover m-0" id="dataTable">
                        <thead>
                            <tr>
                              <th>S.N.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Nagrita Number</th>
                                <th>Nagrita Front</th>
                                <th>Nagrita Back</th>
                                <th>KYC Status</th>
                                <th>KYC Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->kyc?->nagrita_number ?? '-' }}</td>
                                <td>
                                    @if($user->kyc?->nagrita_front)
                                        <a href="{{ asset('storage/' . $user->kyc->nagrita_front) }}" target="_blank">
                                            View
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($user->kyc?->nagrita_back)
                                        <a href="{{ asset('storage/' . $user->kyc->nagrita_back) }}" target="_blank">
                                            View
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge
                                        @if($user->kyc?->verification_status === 'verified') bg-success
                                        @elseif($user->kyc?->verification_status === 'manual_review') bg-warning text-dark
                                        @elseif($user->kyc?->verification_status === 'rejected') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->kyc?->verification_status ?? 'pending')) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $user->kyc?->verification_score ?? '-' }}
                                    @if($user->kyc?->verification_flags)
                                        <small class="d-block text-muted">{{ collect($user->kyc->verification_flags)->pluck('code')->take(2)->implode(', ') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('users.approve', $user->id) }}" method="post">
                                        @csrf
                                        @if($user->is_active)
                                        <button class="btn btn-sm btn-warning">Deactivate</button>
                                        @else
                                        <button class="btn btn-sm btn-success">Activate</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-3 d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
