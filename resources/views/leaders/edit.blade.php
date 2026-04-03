@extends('dashboard-layout.app')

@section('breadcrumb')
<span>Home</span> / <span class="menu-text">Elections/Edit</span>
@endsection

@section('content')
<div class="row">
    <div class="col-xxl-12">
        @include('components.message-flash')

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Form to Edit Entry -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Edit</h5>
                </div>
                <hr>
                <form action="{{ route('leaders.update', $leader->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $leader->name }}" placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Election Dropdown -->
                        <div class="col-md-6">
                            <label for="election" class="form-label">Election</label>
                            <select class="form-control" id="election" name="election_id" required>
                                <option value="">Select Election</option>
                                @foreach($elections as $election)
                                    <option value="{{ $election->id }}" {{ $election->id == $leader->election_id ? 'selected' : '' }}>{{ $election->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Party Fields Container -->
                    <div id="party-container">
                        <div class="party-fields row mb-3">
                            <div class="col-md-6">
                                <label for="party" class="form-label">Party Name</label>
                                <select class="form-control" name="party_id" required>
                                    <option value="">Select Party</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->id }}" {{ $party->id == $leader->party_id ? 'selected' : '' }}>{{ $party->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="logo" class="form-label">Image</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        @if ($leader->logo)
                            <p class="mt-2 mb-1">Current image:</p>
                            <img src="{{ asset('storage/' . $leader->logo) }}" alt="Leader Image" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="row mb-3">
                        <div class="col-md-6 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
