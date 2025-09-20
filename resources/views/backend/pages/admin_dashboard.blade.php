@extends('backend.layouts.master')
@section('title', 'Dashboard')
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Filter Data</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminDashboard') }}" method="GET">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-4">
                                    <label class="form-label">Select Month</label>
                                    <select id="payable_month" name="payable_month"
                                        class="form-control select2-show-search form-select" required>
                                        <option value="">Select Month</option>
                                        @foreach ([
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12,
        ] as $name => $num)
                                            <option value="{{ $num }}"
                                                {{ request('payable_month') == $num ? 'selected' : '' }}>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-4">
                                    <label class="form-label">Select Year</label>
                                    <input type="number" name="payable_year" id="payable_year" class="form-control"
                                        placeholder="Enter year (e.g. 2025)" required
                                        value="{{ request('payable_year', date('Y')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mb-4">
                            <a href="{{ route('adminDashboard') }}" class="btn btn-secondary btn-sm me-2">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <i class="fe fe-users me-1"></i> Total Employee
                                    <h2 class="mb-0 number-font">{{ $total_employees }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <i class="fe fe-dollar-sign me-1"></i> Total Earn
                                    <h2 class="mb-0 number-font">৳{{ number_format($total_earning, 2) }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="leadschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <i class="fe fe-dollar-sign me-1"></i> Total Cost
                                    <h2 class="mb-0 number-font">৳{{ number_format($total_cost, 2) }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="profitchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    @if ($net_profit_or_loss >= 0)
                                        <i class="fe fe-trending-up text-success me-1"></i> Net Profit
                                        <h2 class="mb-0 number-font text-success">
                                            ৳{{ number_format($net_profit_or_loss, 2) }}</h2>
                                    @else
                                        <i class="fe fe-trending-down text-danger me-1"></i> Net Loss
                                        <h2 class="mb-0 number-font text-danger">
                                            ৳{{ number_format(abs($net_profit_or_loss), 2) }}</h2>
                                    @endif
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="costchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ROW-1 END -->

@endsection

@section('custom_js')
    <script src="{{ asset('js/Chart.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            function createChart(id, color) {
                const ctx = document.getElementById(id).getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                        datasets: [{
                            data: [12, 19, 3, 5, 2, 3, 9],
                            borderColor: color,
                            backgroundColor: color + '33', // 20% transparent fill
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Create each chart
            createChart('saleschart', '#6c5ffc'); // Blue
            createChart('leadschart', '#f54394'); // Pink
            createChart('profitchart', '#1fd0a3'); // Green
            createChart('costchart', '#f7b731'); // Yellow
        });
    </script>

@endsection
