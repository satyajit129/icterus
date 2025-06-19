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
                                    <h2 class="mb-0 number-font">{{ $total_earning }}</h2>
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
                                    <h2 class="mb-0 number-font">{{ $total_cost }}</h2>
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
                                        <h2 class="mb-0 number-font text-success">${{ number_format($net_profit_or_loss, 2) }}</h2>
                                    @else
                                        <i class="fe fe-trending-down text-danger me-1"></i> Net Loss
                                        <h2 class="mb-0 number-font text-danger">${{ number_format(abs($net_profit_or_loss), 2) }}</h2>
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
    $(document).ready(function () {
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
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    }
                }
            });
        }

        // Create each chart
        createChart('saleschart', '#6c5ffc');    // Blue
        createChart('leadschart', '#f54394');    // Pink
        createChart('profitchart', '#1fd0a3');   // Green
        createChart('costchart', '#f7b731');     // Yellow
    });
</script>

@endsection
