@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Client Hierarchy</h2>
            <div>
                <a href="{{ route('clients.import.form') }}" class="btn btn-primary me-2">Import Clients (CSV)</a>
                <a href="{{ route('client.create') }}" class="btn btn-primary me-2">Add Client</a>
                <a href="{{ url('swagger/index.html') }}" target="_blank" class="btn btn-success">
                    Swagger Docs
                </a>
            </div>
        </div>
        <ul class="list-group">
            @foreach ($client as $person)
                <li class="list-group-item">
                    <strong>{{ $person->full_name }}</strong>
                    @if ($person->children->isNotEmpty())
                        @include('hierarchy.children', ['children' => $person->children])
                    @endif
                </li>
            @endforeach
        </ul>
         <!-- Pagination links -->
         <div class="mt-4" style='float: right;'>
            {{ $client->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection