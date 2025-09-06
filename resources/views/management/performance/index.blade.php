@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0 text-gray-800">Performance Analytics</h1>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>

            <!-- Performance Overview Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Revenue</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Deals Closed</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dealsWon }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Conversion Rate</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($conversionRate, 1) }}%</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-percentage fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Active Leads</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLeads }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Revenue Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                    <a class="dropdown-item" href="#">Monthly View</a>
                                    <a class="dropdown-item" href="#">Quarterly View</a>
                                    <a class="dropdown-item" href="#">Yearly View</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Distribution -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Team Performance</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Team Performance Metrics</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="performanceTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Team</th>
                                    <th>Revenue</th>
                                    <th>Deals Won</th>
                                    <th>Conversion Rate</th>
                                    <th>Avg Deal Size</th>
                                    <th>Active Leads</th>
                                    <th>Performance Score</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($performanceMetrics as $metric)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-users text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $metric->team->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $metric->team->users->count() }} members</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($metric->revenue_generated, 2) }}</td>
                                    <td>{{ $metric->deals_won }}</td>
                                    <td>{{ number_format($metric->conversion_rate, 1) }}%</td>
                                    <td>${{ number_format($metric->getAverageDealSize(), 2) }}</td>
                                    <td>{{ $metric->active_leads }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">{{ $metric->performance_score }}</span>
                                            <div class="progress flex-fill" style="height: 6px;">
                                                <div class="progress-bar 
                                                    @if($metric->performance_score >= 90) bg-success
                                                    @elseif($metric->performance_score >= 70) bg-info
                                                    @elseif($metric->performance_score >= 50) bg-warning
                                                    @else bg-danger
                                                    @endif" 
                                                     role="progressbar" 
                                                     style="width: {{ $metric->performance_score }}%"
                                                     aria-valuenow="{{ $metric->performance_score }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($metric->getTrend() === 'up')
                                            <i class="fas fa-arrow-up text-success"></i>
                                        @elseif($metric->getTrend() === 'down')
                                            <i class="fas fa-arrow-down text-danger"></i>
                                        @else
                                            <i class="fas fa-minus text-warning"></i>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Individual Performance -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performers</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($topPerformers as $performer)
                        <div class="col-lg-4 mb-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="icon-circle bg-success mx-auto mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-star text-white" style="font-size: 1.5rem; line-height: 60px;"></i>
                                        </div>
                                        <h5 class="mb-0">{{ $performer->name }}</h5>
                                        <small class="text-muted">{{ $performer->email }}</small>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="font-weight-bold">${{ number_format($performer->total_revenue ?? 0, 0) }}</div>
                                            <div class="text-xs text-gray-600">Revenue</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="font-weight-bold">{{ $performer->deals_won ?? 0 }}</div>
                                            <div class="text-xs text-gray-600">Deals</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Performance Data</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="GET" action="{{ route('management.performance') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="period">Time Period</label>
                        <select name="period" id="period" class="form-control">
                            <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="team">Team</label>
                        <select name="team" id="team" class="form-control">
                            <option value="">All Teams</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $('#performanceTable').DataTable({
        "pageLength": 10,
        "order": [[ 6, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [7] }
        ]
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueLabels) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenueData) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Performance Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($performanceLabels) !!},
            datasets: [{
                data: {!! json_encode($performanceData) !!},
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
@endsection
