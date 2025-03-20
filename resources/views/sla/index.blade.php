@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>SLA Management</h1>
        <a href="{{ route('slas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create New SLA
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>SLA List</h5>
        </div>
        <div class="card-body">
            @if(count($slas) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Response Time (mins)</th>
                                <th>Resolution Time (mins)</th>
                                <th>Penalty (RM)</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slas as $sla)
                                <tr>
                                    <td>{{ $sla->id }}</td>
                                    <td><span class="badge bg-{{ $sla->status === 'Critical' ? 'danger' : ($sla->status === 'High' ? 'warning' : ($sla->status === 'Medium' ? 'info' : 'success')) }}">{{ $sla->status }}</span></td>
                                    <td>{{ $sla->response_time }}</td>
                                    <td>{{ $sla->resolution_time }}</td>
                                    <td>RM {{ number_format($sla->penalty, 2) }}</td>
                                    <td>{{ $sla->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('slas.edit', $sla->id) }}" class="btn btn-sm btn-info">
                                            Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $sla->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Delete Modal for each SLA -->
                                <div class="modal fade" id="deleteModal{{ $sla->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $sla->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $sla->id }}">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this SLA?</p>
                                                <p><strong>ID:</strong> {{ $sla->id }}</p>
                                                <p><strong>Status:</strong> {{ $sla->status }}</p>
                                                <p><strong>Response Time:</strong> {{ $sla->response_time }} minutes</p>
                                                <p><strong>Resolution Time:</strong> {{ $sla->resolution_time }} minutes</p>
                                                <p><strong>Penalty:</strong> RM {{ number_format($sla->penalty, 2) }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('slas.destroy', $sla->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No SLAs found. Click "Create New SLA" to add one.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
