@extends('layouts.main')

@section('content')
<h3>Import Clients via CSV</h3>

<form action="{{ route('clients.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
    @csrf
    <div class="mb-3">
        <label for="csv_file" class="form-label">Upload CSV File</label>
        <input type="file" name="csv_file" class="form-control" required accept=".csv">
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
</form>
@endsection