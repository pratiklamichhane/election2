@extends('dashboard-layout.app')

@section('breadcrumb')
<span>Home</span> / <span class="menu-text">Leaders/Create</span>
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
    <!-- Form to Add New Entry -->
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Add</h5>
        </div>
        <hr>
        <form action="{{route('leaders.store')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row mb-3">
          <div class="col-md-4">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
            </div>
            </div>
          <div class="row mb-3">
            <!-- Election Dropdown -->
            <div class="col-md-6">
              <label for="election" class="form-label">Election</label>
              <select class="form-control" id="election" name="election_id" required>
                <option value="">Select Election</option>
                @foreach($elections as $election)
                  <option value="{{ $election->id }}">{{ $election->name }}</option>
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
                    <option value="{{ $party->id }}">{{ $party->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="logo" class="form-label">Image</label>
              <input type="file" id="logo" name="logo" class="form-control" accept="image/*" required>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row mb-3">
            <div class="col-md-6 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

