@extends('layouts.main')

@section('content')
    <h2>Add Client</h2>
    <form action="{{ route('client.store') }}" method="POST" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label for="full_name" class="form-label">Full Name*</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="col-md-6">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="col-md-6">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" name="dob" class="form-control">
        </div>

        <div class="col-md-6">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" name="balance" step="0.01" class="form-control">
        </div>

        <div class="col-md-6">
            <label for="parent_id" class="form-label">Parent Client (optional)</label>
            <select name="parent_id" class="form-select">
                <option value="">-- None --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success">Add Client</button>
            <a href="{{ url('/') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
@endsection
