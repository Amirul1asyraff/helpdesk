@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Create New Ticket</span>
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
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

                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="project_id">Project</label>
                            <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id" required>
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="responsible_by">Responsible By</label>
                            <select name="responsible_by" class="form-control @error('responsible_by') is-invalid @enderror" id="responsible_by">
                                <option value="">Select Responsible User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('responsible_by') == $user->id ? 'selected' : '' }}>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3" placeholder="Enter ticket description" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="sla_id">SLA Level</label>
                            <select name="sla_id" class="form-control @error('sla_id') is-invalid @enderror" id="sla_id" required>
                                <option value="">Select SLA</option>
                                @foreach($slas as $sla)
                                    <option value="{{ $sla->id }}" {{ old('sla_id') == $sla->id ? 'selected' : '' }}>
                                        {{ $sla->status }} - Response: {{ $sla->response_time }} mins, Resolution: {{ $sla->resolution_time }} mins, Penalty: RM {{ $sla->penalty }}
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
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Open</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Closed</option>
                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Escalated</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Create Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
