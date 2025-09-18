@extends('backend.layouts.master')
@section('title', 'Facebook Lead Details')
@section('custom_css')
    <style>
        .lead-header-card {
            background: linear-gradient(135deg, #1877f2 0%, #42a5f5 100%);
            color: white;
        }

        .info-card {
            border-left: 4px solid #1877f2;
        }

        .field-item {
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 0;
        }

        .field-item:last-child {
            border-bottom: none;
        }

        .field-name {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }

        .field-value {
            color: #212529;
            font-size: 0.95rem;
        }

        .lead-id {
            font-family: monospace;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .field-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            margin: 0.1rem;
        }
    </style>
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

            <!-- Form Fields Data -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Form Fields Data</h3>
                </div>
                <div class="card-body">
                    @if ($lead->field_data && count($lead->field_data) > 0)
                        @foreach ($lead->field_data as $field)
                            <div class="field-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="field-name">{{ $field['name'] }}</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="field-value">
                                            @if (isset($field['values']) && is_array($field['values']) && count($field['values']) > 0)
                                                {{ implode(', ', $field['values']) }}
                                            @elseif (isset($field['values']) && !is_array($field['values']))
                                                {{ $field['values'] }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No field data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection
