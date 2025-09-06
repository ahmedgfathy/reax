@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0 text-gray-800">Goals & Targets</h1>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGoalModal">
                        <i class="fas fa-plus"></i> Add Goal
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-chart-bar"></i> Reports
                    </button>
                </div>
            </div>

            <!-- Goals Overview Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Goals</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $goals->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bullseye fa-2x text-gray-300"></i>
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
                                        Achieved Goals</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $goals->where('status', 'achieved')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-trophy fa-2x text-gray-300"></i>
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
                                        In Progress</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $goals->where('status', 'in_progress')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                        Avg Progress</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($goals->avg('progress_percentage'), 1) }}%</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-percentage fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('management.goals.index') }}" class="row align-items-end">
                        <div class="col-md-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="achieved" {{ request('status') === 'achieved' ? 'selected' : '' }}>Achieved</option>
                                <option value="missed" {{ request('status') === 'missed' ? 'selected' : '' }}>Missed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="type">Goal Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="revenue" {{ request('type') === 'revenue' ? 'selected' : '' }}>Revenue</option>
                                <option value="sales" {{ request('type') === 'sales' ? 'selected' : '' }}>Sales</option>
                                <option value="leads" {{ request('type') === 'leads' ? 'selected' : '' }}>Leads</option>
                                <option value="activity" {{ request('type') === 'activity' ? 'selected' : '' }}>Activity</option>
                                <option value="kpi" {{ request('type') === 'kpi' ? 'selected' : '' }}>KPI</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="period">Period</label>
                            <select name="period" id="period" class="form-control">
                                <option value="">All Periods</option>
                                <option value="daily" {{ request('period') === 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('period') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('period') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ request('period') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ request('period') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('management.goals.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Goals List -->
            <div class="row">
                @foreach($goals as $goal)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">{{ $goal->title }}</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                    <a class="dropdown-item" href="{{ route('management.goals.show', $goal) }}">
                                        <i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400"></i> View Details
                                    </a>
                                    <a class="dropdown-item" href="{{ route('management.goals.edit', $goal) }}">
                                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('management.goals.destroy', $goal) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirm('Are you sure you want to delete this goal?')">
                                            <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-xs font-weight-bold text-gray-600">Progress</span>
                                        <span class="text-xs font-weight-bold">{{ $goal->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar 
                                            @if($goal->progress_percentage >= 100) bg-success
                                            @elseif($goal->progress_percentage >= 75) bg-info  
                                            @elseif($goal->progress_percentage >= 50) bg-warning
                                            @else bg-danger
                                            @endif" 
                                             role="progressbar" 
                                             style="width: {{ min($goal->progress_percentage, 100) }}%"
                                             aria-valuenow="{{ $goal->progress_percentage }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="text-xs text-gray-600">Current</div>
                                    <div class="font-weight-bold">{{ number_format($goal->current_value, 2) }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-xs text-gray-600">Target</div>
                                    <div class="font-weight-bold">{{ number_format($goal->target_value, 2) }}</div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-xs text-gray-600">Type</div>
                                    <span class="badge badge-primary">{{ ucfirst($goal->type) }}</span>
                                </div>
                                <div class="col-6">
                                    <div class="text-xs text-gray-600">Status</div>
                                    <span class="badge 
                                        @if($goal->status === 'achieved') badge-success
                                        @elseif($goal->status === 'in_progress') badge-info
                                        @elseif($goal->status === 'missed') badge-danger
                                        @else badge-secondary
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $goal->status)) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($goal->target_date)
                            <div class="mt-2">
                                <div class="text-xs text-gray-600">Target Date</div>
                                <div class="text-sm">{{ $goal->target_date->format('M d, Y') }}</div>
                                @if($goal->target_date->isPast() && $goal->status !== 'achieved')
                                    <small class="text-danger">Overdue</small>
                                @endif
                            </div>
                            @endif
                            
                            @if($goal->assignedUser)
                            <div class="mt-2">
                                <div class="text-xs text-gray-600">Assigned to</div>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <div class="icon-circle bg-info">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    </div>
                                    <div class="text-sm">{{ $goal->assignedUser->name }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $goals->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Goal Modal -->
<div class="modal fade" id="createGoalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Goal</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('management.goals.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Goal Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Goal Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="revenue">Revenue</option>
                                    <option value="sales">Sales</option>
                                    <option value="leads">Leads</option>
                                    <option value="activity">Activity</option>
                                    <option value="kpi">KPI</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period">Period</label>
                                <select class="form-control" id="period" name="period" required>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_value">Target Value</label>
                                <input type="number" class="form-control" id="target_value" name="target_value" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_date">Target Date</label>
                                <input type="date" class="form-control" id="target_date" name="target_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_to">Assign To</label>
                                <select class="form-control" id="assigned_to" name="assigned_to">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="team_id">Team</label>
                                <select class="form-control" id="team_id" name="team_id">
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Goal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
