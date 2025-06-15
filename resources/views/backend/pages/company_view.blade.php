@extends('backend.layouts.master')

@section('title', 'Company Info')

@section('custom_css')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printSection, #printSection * {
                visibility: visible;
            }
            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
@endsection


@section('content')
    <div class="page-header">
        <h1 class="page-title">Company Info</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminEmployeeList') }}">Company Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company Info</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card" id="employeeInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Company Information</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()" title="Print">
                        <i class="fe fe-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card-body" id="printSection">
                    <!-- Profile Image -->
                    <div class="text-center mb-4">
                        <img src="{{ $company->logo ? asset('uploads/' . $company->logo) : asset('default-profile.png') }}"
                             alt="Employee Photo"
                             class="rounded-circle shadow"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h4 class="mt-3">{{ $company->name ?? 'N/A' }}</h4>
                    </div>

                    <!-- Company Info Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%;">CEO Name</th>
                                    <td style="width: 50%;">{{ $company->ceo_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>CEO Phone</th>
                                    <td>{{ $company->ceo_phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>CEO Picture</th>
                                    <td>
                                        @if(isset($company->ceo_picture))
                                            <img src="{{ asset('uploads/' . $company->ceo_picture) }}" alt="CEO Picture" style="height: 80px;">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $company->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Categories</th>
                                    <td>
                                        @if(!empty($company->categories))
                                            {{ is_array($company->categories) ? implode(', ', $company->categories) : $company->categories }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection
