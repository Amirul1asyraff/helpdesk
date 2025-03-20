@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Alerts for Success and Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Ticket Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Ticket Details: {{ $ticket->codes }}</h4>
                            <div>
                                <a href="{{ route('tickets.edit', $ticket->uuid) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Ticket Information Column -->
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">Ticket Information</h5>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Ticket Code:</div>
                                    <div class="col-md-8">{{ $ticket->codes }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Project:</div>
                                    <div class="col-md-8">{{ $ticket->project->name ?? 'N/A' }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Status:</div>
                                    <div class="col-md-8">
                                        @php
                                            $statusClass = '';
                                            $statusText = '';

                                            switch ($ticket->status) {
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
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Description:</div>
                                    <div class="col-md-8">
                                        <div class="p-3 bg-light rounded">
                                            {{ $ticket->description ?? 'No description provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Created By:</div>
                                    <div class="col-md-8">{{ $ticket->createdBy->name ?? 'N/A' }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Responsible:</div>
                                    <div class="col-md-8">{{ $ticket->responsibleBy->name ?? 'N/A' }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Created At:</div>
                                    <div class="col-md-8">
                                        {{ $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Last Updated:</div>
                                    <div class="col-md-8">
                                        {{ $ticket->updated_at ? $ticket->updated_at->format('Y-m-d H:i:s') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responses Card - Separate from Ticket Information -->
                <div class="card">
                    <div class="card-header">
                        <h4>Responses</h4>
                    </div>
                    <div class="card-body">
                        @if (isset($ticket->responses) && count($ticket->responses) > 0)
                            <div class="list-group mb-4">
                                @foreach ($ticket->responses as $response)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between mb-2">
                                            <h6 class="mb-0">
                                                @php
                                                    // Handle the relationship correctly based on response_by field
                                                    $responseUser = App\Models\User::find($response->response_by);
                                                    $userName = $responseUser ? $responseUser->name : 'Unknown User';
                                                @endphp
                                                <strong>{{ $userName }}</strong>
                                            </h6>
                                            <div>
                                                <small>
                                                    Created:
                                                    {{ $response->created_at ? $response->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                                                    @if ($response->updated_at && $response->created_at->ne($response->updated_at))
                                                        <span class="text-muted">
                                                            <br>Edited:
                                                            {{ $response->updated_at->format('Y-m-d H:i:s') }}
                                                        </span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        <p class="mb-2">{{ $response->content ?? 'No response content' }}</p>

                                        @if (auth()->id() == $response->response_by || auth()->user())
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="button" class="btn btn-outline-primary me-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editResponseModal{{ $response->id }}">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteResponseModal{{ $response->id }}">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Edit Response Modal -->
                                    <div class="modal fade" id="editResponseModal{{ $response->id }}"
                                        tabindex="-1" aria-labelledby="editResponseModalLabel{{ $response->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="editResponseModalLabel{{ $response->id }}">Edit
                                                        Response</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('responses.update', $response->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="edit_message_{{ $response->id }}">Response
                                                                Content</label>
                                                            <textarea class="form-control" id="edit_message_{{ $response->id }}" name="message" rows="3" required>{{ $response->content }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update
                                                            Response</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Response Modal -->
                                    <div class="modal fade" id="deleteResponseModal{{ $response->id }}"
                                        tabindex="-1"
                                        aria-labelledby="deleteResponseModalLabel{{ $response->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteResponseModalLabel{{ $response->id }}">Confirm
                                                        Deletion</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this response?</p>
                                                    <p class="text-muted fst-italic">
                                                        "{{ Str::limit($response->content, 100) }}"</p>
                                                    <p class="text-danger"><small>This action cannot be
                                                            undone.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('responses.destroy', $response->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="ticket_id"
                                                            value="{{ $ticket->id }}">
                                                        <button type="submit"
                                                            class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-4">No responses yet.</div>
                        @endif

                        <!-- Add Response Form -->
                        <form action="{{ route('responses.store') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                            <div class="form-group mb-3">
                                <label for="message">Add Response</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message" rows="3"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Response</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
