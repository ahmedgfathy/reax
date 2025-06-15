@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0 text-gray-800">Team Activities</h1>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                        <i class="fas fa-plus"></i> Log Activity
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-calendar"></i> Schedule
                    </button>
                </div>
            </div>

            <!-- Activity Overview Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Activities</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activities->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tasks fa-2x text-gray-300"></i>
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
                                        Completed Today</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCompleted }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                        Pending</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingActivities }}</div>
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
                                        Collaboration Score</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($collaborationScore, 1) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('management.activities') }}" class="row align-items-end">
                        <div class="col-md-3">
                            <label for="activity_type">Activity Type</label>
                            <select name="activity_type" id="activity_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="call" {{ request('activity_type') === 'call' ? 'selected' : '' }}>Call</option>
                                <option value="meeting" {{ request('activity_type') === 'meeting' ? 'selected' : '' }}>Meeting</option>
                                <option value="email" {{ request('activity_type') === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="task" {{ request('activity_type') === 'task' ? 'selected' : '' }}>Task</option>
                                <option value="follow_up" {{ request('activity_type') === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="team_id">Team</label>
                            <select name="team_id" id="team_id" class="form-control">
                                <option value="">All Teams</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('management.activities') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activities Timeline -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach($activities as $activity)
                                <div class="timeline-item mb-4">
                                    <div class="timeline-marker 
                                        @if($activity->status === 'completed') bg-success
                                        @elseif($activity->status === 'in_progress') bg-warning
                                        @elseif($activity->status === 'cancelled') bg-danger
                                        @else bg-info
                                        @endif">
                                        <i class="fas 
                                            @if($activity->activity_type === 'call') fa-phone
                                            @elseif($activity->activity_type === 'meeting') fa-calendar
                                            @elseif($activity->activity_type === 'email') fa-envelope
                                            @elseif($activity->activity_type === 'task') fa-tasks
                                            @else fa-bell
                                            @endif text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $activity->title }}</h6>
                                                <p class="text-gray-600 mb-1">{{ $activity->description }}</p>
                                                <div class="d-flex align-items-center text-sm text-gray-500">
                                                    <i class="fas fa-user mr-1"></i>
                                                    <span class="mr-3">{{ $activity->createdBy->name }}</span>
                                                    <i class="fas fa-users mr-1"></i>
                                                    <span class="mr-3">{{ $activity->team->name }}</span>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    <span>{{ $activity->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge 
                                                    @if($activity->status === 'completed') badge-success
                                                    @elseif($activity->status === 'in_progress') badge-warning
                                                    @elseif($activity->status === 'cancelled') badge-danger
                                                    @else badge-info
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                                </span>
                                                @if($activity->priority === 'high')
                                                    <span class="badge badge-danger ml-1">High Priority</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($activity->due_date)
                                        <div class="mt-2">
                                            <small class="text-gray-500">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                Due: {{ $activity->due_date->format('M d, Y H:i') }}
                                                @if($activity->due_date->isPast() && $activity->status !== 'completed')
                                                    <span class="text-danger ml-1">(Overdue)</span>
                                                @endif
                                            </small>
                                        </div>
                                        @endif

                                        @if($activity->participants->count() > 0)
                                        <div class="mt-2">
                                            <small class="text-gray-500">Participants:</small>
                                            @foreach($activity->participants as $participant)
                                                <span class="badge badge-light ml-1">{{ $participant->name }}</span>
                                            @endforeach
                                        </div>
                                        @endif

                                        @if($activity->outcome)
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <small class="text-gray-600">
                                                <strong>Outcome:</strong> {{ $activity->outcome }}
                                            </small>
                                        </div>
                                        @endif

                                        <div class="mt-2">
                                            <div class="btn-group btn-group-sm">
                                                @if($activity->status !== 'completed')
                                                <button type="button" class="btn btn-outline-success btn-sm" 
                                                        onclick="updateActivityStatus({{ $activity->id }}, 'completed')">
                                                    <i class="fas fa-check"></i> Complete
                                                </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-comment"></i> Comment
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Activity Stats -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Activity Statistics</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>

                    <!-- Upcoming Activities -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Upcoming Activities</h6>
                        </div>
                        <div class="card-body">
                            @foreach($upcomingActivities as $activity)
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas 
                                            @if($activity->activity_type === 'call') fa-phone
                                            @elseif($activity->activity_type === 'meeting') fa-calendar
                                            @elseif($activity->activity_type === 'email') fa-envelope
                                            @else fa-tasks
                                            @endif text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $activity->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->due_date->format('M d, Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->team->name }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Team Collaboration -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Team Collaboration</h6>
                        </div>
                        <div class="card-body">
                            @foreach($collaborativeActivities as $activity)
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-handshake text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $activity->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->participants->count() }} participants
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
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
</div>

<!-- Create Activity Modal -->
<div class="modal fade" id="createActivityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log New Activity</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('management.activities.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Activity Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="activity_type">Type</label>
                                <select class="form-control" id="activity_type" name="activity_type" required>
                                    <option value="call">Call</option>
                                    <option value="meeting">Meeting</option>
                                    <option value="email">Email</option>
                                    <option value="task">Task</option>
                                    <option value="follow_up">Follow Up</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="team_id">Team</label>
                                <select class="form-control" id="team_id" name="team_id">
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Log Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Activity Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'In Progress', 'Scheduled', 'Cancelled'],
            datasets: [{
                data: [{{ $completedCount }}, {{ $inProgressCount }}, {{ $scheduledCount }}, {{ $cancelledCount }}],
                backgroundColor: ['#1cc88a', '#f6c23e', '#4e73df', '#e74a3b']
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

function updateActivityStatus(activityId, status) {
    fetch(`/management/activities/${activityId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -38px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-content {
    background: #f8f9fa;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 1rem;
    position: relative;
}

.timeline-content::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 10px;
    width: 0;
    height: 0;
    border-top: 6px solid transparent;
    border-bottom: 6px solid transparent;
    border-right: 6px solid #e3e6f0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -27px;
    top: 12px;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}
</style>
@endpush
@endsection
