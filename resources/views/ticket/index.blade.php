@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Left: Title -->
                        <h4 class="mb-0">Ticket Management</h4>

                        <!-- Right: Search form and Create button -->
                        <div class="d-flex align-items-center gap-3">
                            <form action="" method="GET" class="mb-0">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search Ticket" value="{{ request()->search }}">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                    <button class="btn btn-outline-danger" type="button" onclick="window.location.href='{{ route('tickets.index') }}'">X</button>
                                </div>
                            </form>

                            <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Ticket
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        @if(count($tickets) > 0)
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Ticket Code</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Project</th>
                                        <th scope="col">Responsible</th>
                                        <th scope="col">Response Time</th>
                                        <th scope="col">Resolution Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->id }}</td>
                                            <td>{{ $ticket->codes }}</td>
                                            <td>{{ $ticket->createdBy->name ?? 'N/A' }}</td>
                                            <td>{{ $ticket->project->name ?? 'N/A' }}</td>
                                            <td>{{ $ticket->responsibleBy->name ?? 'N/A' }}</td>
                                            <td>{{ $ticket->response_time }} mins</td>
                                            <td>{{ $ticket->resolution_time }} mins</td>
                                            <td>
                                                @php
                                                    $statusClass = '';
                                                    $statusText = '';

                                                    switch($ticket->status) {
                                                        case 0:
                                                            $statusClass = 'bg-primary';
                                                            $statusText = 'Open';
                                                            break;
                                                        case 1:
                                                            $statusClass = 'bg-success';
                                                            $statusText = 'Closed';
                                                            break;
                                                        case 2:
                                                            $statusClass = 'bg-danger';
                                                            $statusText = 'Escalated';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-secondary';
                                                            $statusText = 'Unknown';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('tickets.show', $ticket->uuid) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('tickets.edit', $ticket->uuid) }}" class="btn btn-sm btn-warning me-2">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $ticket->id }}">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </div>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $ticket->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $ticket->id }}">Confirm Deletion</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete ticket <strong>{{ $ticket->codes }}</strong>?
                                                                <p class="text-danger"><small>This action cannot be undone.</small></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="justify-content-center">
                                {{ $tickets->links() }}
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                No tickets found. <a href="{{ route('tickets.create') }}">Create your first ticket</a>.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
