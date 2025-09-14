@extends('backend.layouts.master')
@section('title', 'Facebook Lead Details')
@section('custom_css')

@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Lead Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leadList') }}">Leads</a></li>
                <li class="breadcrumb-item active">{{ $lead->lead_id }}</li>
            </ol>
        </div>
    </div>

    <div class="row">

        <!-- Lead Details -->
        <div class="col-md-12">

            <!-- Edit Field Data -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Edit Field Data</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('leadSave', $lead->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="expression" class="form-label">Expression</label>
                            <select id="expression" name="expression" class="form-select" required>
                                <option value="">-- Select Expression --</option>
                                <option value="1" {{ old('expression', $lead->expression) == 1 ? 'selected' : '' }}>
                                    Interested</option>
                                <option value="2" {{ old('expression', $lead->expression) == 2 ? 'selected' : '' }}>Not
                                    Interested</option>
                                <option value="3" {{ old('expression', $lead->expression) == 3 ? 'selected' : '' }}>
                                    Converted</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea id="comment" name="comment" class="form-control" rows="3" placeholder="Enter comment...">{{ old('comment', $lead->comment) }}</textarea>
                        </div>

                        <div class="form-check form-switch mb-3" style="margin-left: 1rem;">
                            <input class="form-check-input" type="checkbox" id="followup" name="followup" value="1"
                                {{ old('followup', $lead->followup) ? 'checked' : '' }}>
                            <label class="form-check-label" for="followup">Add to Follow-up</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('custom_js')
@endsection
