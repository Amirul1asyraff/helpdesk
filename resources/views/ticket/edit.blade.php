@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Edit Ticket: {{ $ticket->codes }}</span>
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Error!</strong> Please check the form for errors.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="codes">Ticket Code</label>
                            <input type="text" class="form-control" id="codes" value="{{ $ticket->codes }}" disabled readonly>
                            <small class="form-text text-muted">Ticket code cannot be changed.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="created_by_display">Created By</label>
                            <input type="text" class="form-control" id="created_by_display" value="{{ $ticket->createdBy->name ?? 'Unknown User' }}" readonly>
                            <input type="hidden" name="created_by" value="{{ $ticket->created_by }}">
                            <small class="form-text text-muted">The creator of the ticket cannot be changed.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="project_id_display">Project</label>
                            <input type="text" class="form-control" id="project_id_display" value="{{ $ticket->project->name ?? 'Unknown Project' }}" readonly>
                            <input type="hidden" name="project_id" value="{{ $ticket->project_id }}">
                            <small class="form-text text-muted">The project cannot be changed after ticket creation.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="responsible_by">Responsible By</label>
                            <select name="responsible_by" class="form-control @error('responsible_by') is-invalid @enderror" id="responsible_by" required>
                                <option value="">Select Responsible User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('responsible_by') ?? $ticket->responsible_by) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsible_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3" placeholder="Enter ticket description" required>{{ old('description') ?? $ticket->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="sla_id">SLA Level</label>
                            <select name="sla_id" class="form-control @error('sla_id') is-invalid @enderror" id="sla_id" required>
                                <option value="">Select SLA</option>
                                @foreach($slas as $sla)
                                    <option value="{{ $sla->id }}" {{ (old('sla_id') ?? $ticket->sla_id) == $sla->id ? 'selected' : '' }}>
                                        {{ $sla->status }} - Response: {{ $sla->response_time }} mins, Resolution: {{ $sla->resolution_time }} mins, Penalty: RM{{ number_format($sla->penalty, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sla_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" required>
                                <option value="">Select Status</option>
                                <option value="0" {{ (old('status') ?? $ticket->status) == '0' ? 'selected' : '' }}>Open</option>
                                <option value="1" {{ (old('status') ?? $ticket->status) == '1' ? 'selected' : '' }}>Closed</option>
                                <option value="2" {{ (old('status') ?? $ticket->status) == '2' ? 'selected' : '' }}>Escalated</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Created At</label>
                            <input type="text" class="form-control" value="{{ $ticket->created_at->format('Y-m-d H:i:s') }}" disabled readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Last Updated</label>
                            <input type="text" class="form-control" value="{{ $ticket->updated_at->format('Y-m-d H:i:s') }}" disabled readonly>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
