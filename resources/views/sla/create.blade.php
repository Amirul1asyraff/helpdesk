@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Create New SLA</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('slas.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Critical" {{ old('status') == 'Critical' ? 'selected' : '' }}>Critical</option>
                                <option value="High" {{ old('status') == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Medium" {{ old('status') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Low" {{ old('status') == 'Low' ? 'selected' : '' }}>Low</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="response_time" class="form-label">Response Time (minutes)</label>
                            <input type="number" class="form-control @error('response_time') is-invalid @enderror" id="response_time" name="response_time" value="{{ old('response_time') }}" required>
                            @error('response_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="resolution_time" class="form-label">Resolution Time (minutes)</label>
                            <input type="number" class="form-control @error('resolution_time') is-invalid @enderror" id="resolution_time" name="resolution_time" value="{{ old('resolution_time') }}" required>
                            @error('resolution_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="penalty" class="form-label">Penalty (RM)</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       class="form-control @error('penalty') is-invalid @enderror"
                                       id="penalty"
                                       name="penalty"
                                       value="{{ old('penalty', '0.00') }}"
                                       placeholder="0.00"
                                       required>
                                @error('penalty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Enter penalty amount in Malaysian Ringgit (RM).</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('slas.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create SLA</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
