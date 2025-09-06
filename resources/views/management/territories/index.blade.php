@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h2 mb-1 text-dark fw-bold">Territory Management</h1>
            <p class="text-muted mb-0">Manage and optimize your sales territories for maximum performance</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mapViewModal">
                <i class="fas fa-map me-2"></i>Map View
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTerritoryModal">
                <i class="fas fa-plus me-2"></i>Add Territory
            </button>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary bg-gradient me-3">
                            <i class="fas fa-map-marked-alt text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="h4 mb-1 text-dark fw-bold">{{ $territories->count() }}</h3>
                            <p class="text-muted mb-0 small">Total Territories</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success bg-gradient me-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="h4 mb-1 text-dark fw-bold">{{ $territories->where('is_active', true)->count() }}</h3>
                            <p class="text-muted mb-0 small">Active Territories</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info bg-gradient me-3">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="h4 mb-1 text-dark fw-bold">${{ number_format($territories->sum('total_revenue'), 0) }}</h3>
                            <p class="text-muted mb-0 small">Total Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning bg-gradient me-3">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="h4 mb-1 text-dark fw-bold">
                                {{ number_format($territories->where('target_revenue', '>', 0)->avg(fn($t) => $t->getRevenueAchievementPercentage()), 1) }}%
                            </h3>
                            <p class="text-muted mb-0 small">Avg Performance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label text-muted small fw-semibold">Search Territories</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput" 
                               placeholder="Search by name, code, or manager...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label text-muted small fw-semibold">Type</label>
                    <select class="form-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="geographic">Geographic</option>
                        <option value="demographic">Demographic</option>
                        <option value="product">Product-based</option>
                        <option value="account">Account-based</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label text-muted small fw-semibold">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label text-muted small fw-semibold">Performance</label>
                    <select class="form-select" id="performanceFilter">
                        <option value="">All Performance</option>
                        <option value="high">High (>80%)</option>
                        <option value="medium">Medium (40-80%)</option>
                        <option value="low">Low (<40%)</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-12">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Territories Cards Grid -->
    <div class="row g-4" id="territoriesGrid">
        @foreach($territories as $territory)
        <div class="col-xl-4 col-lg-6 col-md-6 territory-card" 
             data-name="{{ strtolower($territory->name) }}"
             data-code="{{ strtolower($territory->code) }}"
             data-type="{{ $territory->type }}"
             data-status="{{ $territory->is_active ? 'active' : 'inactive' }}"
             data-performance="{{ $territory->getRevenueAchievementPercentage() }}"
             data-manager="{{ strtolower($territory->manager->name ?? 'unassigned') }}">
            <div class="card territory-item border-0 shadow-sm h-100 position-relative">
                <!-- Status Badge -->
                <div class="position-absolute top-0 end-0 m-3">
                    @if($territory->is_active)
                        <span class="badge bg-success bg-gradient px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>Active
                        </span>
                    @else
                        <span class="badge bg-danger bg-gradient px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i>Inactive
                        </span>
                    @endif
                </div>

                <div class="card-body p-4">
                    <!-- Territory Header -->
                    <div class="d-flex align-items-start mb-3">
                        <div class="territory-avatar me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center 
                                        {{ $territory->type === 'geographic' ? 'bg-primary' : 
                                           ($territory->type === 'demographic' ? 'bg-info' : 
                                           ($territory->type === 'product' ? 'bg-warning' : 'bg-secondary')) }} bg-gradient"
                                 style="width: 48px; height: 48px;">
                                <i class="fas {{ $territory->type === 'geographic' ? 'fa-map-marker-alt' : 
                                                 ($territory->type === 'demographic' ? 'fa-users' : 
                                                 ($territory->type === 'product' ? 'fa-cube' : 'fa-building')) }} text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1 text-dark fw-bold">{{ $territory->name }}</h5>
                            <p class="text-muted mb-0 small">{{ $territory->code }}</p>
                            @if($territory->description)
                                <p class="text-muted small mb-0 mt-1" style="font-size: 0.875rem;">
                                    {{ Str::limit($territory->description, 60) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Territory Type Badge -->
                    <div class="mb-3">
                        <span class="badge {{ $territory->type === 'geographic' ? 'bg-primary' : 
                                             ($territory->type === 'demographic' ? 'bg-info' : 
                                             ($territory->type === 'product' ? 'bg-warning text-dark' : 'bg-secondary')) }} bg-gradient px-3 py-2">
                            {{ ucfirst($territory->type) }}
                        </span>
                    </div>

                    <!-- Manager Info -->
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                        <div class="me-3">
                            @if($territory->manager)
                                <div class="rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center"
                                     style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-white small"></i>
                                </div>
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                     style="width: 32px; height: 32px;">
                                    <i class="fas fa-user-slash text-white small"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 small text-muted fw-semibold">Manager</p>
                            <p class="mb-0 fw-semibold">{{ $territory->manager->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>

                    <!-- Revenue Section -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-semibold">Revenue Performance</span>
                            <span class="fw-bold {{ $territory->getRevenueAchievementPercentage() >= 80 ? 'text-success' : 
                                                   ($territory->getRevenueAchievementPercentage() >= 40 ? 'text-warning' : 'text-danger') }}">
                                {{ $territory->getRevenueAchievementPercentage() }}%
                            </span>
                        </div>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar {{ $territory->getRevenueAchievementPercentage() >= 80 ? 'bg-success' : 
                                                       ($territory->getRevenueAchievementPercentage() >= 40 ? 'bg-warning' : 'bg-danger') }} bg-gradient" 
                                 role="progressbar" 
                                 style="width: {{ min($territory->getRevenueAchievementPercentage(), 100) }}%"
                                 aria-valuenow="{{ $territory->getRevenueAchievementPercentage() }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>${{ number_format($territory->total_revenue, 0) }}</span>
                            <span>Target: ${{ number_format($territory->target_revenue, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex gap-2">
                        <a href="{{ route('management.territories.show', $territory) }}" 
                           class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                        <a href="{{ route('management.territories.edit', $territory) }}" 
                           class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('management.territories.show', $territory) }}">
                                        <i class="fas fa-analytics me-2"></i>Analytics
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('management.territories.destroy', $territory) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirm('Are you sure you want to delete this territory?')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-5" style="display: none;">
        <div class="mb-4">
            <i class="fas fa-search fa-3x text-muted"></i>
        </div>
        <h4 class="text-muted">No territories found</h4>
        <p class="text-muted">Try adjusting your search criteria or filters</p>
        <button type="button" class="btn btn-outline-primary" id="clearFiltersEmpty">
            Clear All Filters
        </button>
    </div>
</div>

<!-- Create Territory Modal -->
<div class="modal fade" id="createTerritoryModal" tabindex="-1" aria-labelledby="createTerritoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="createTerritoryModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Create New Territory
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('management.territories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label text-muted small fw-semibold">Territory Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg border-2" id="name" name="name" 
                                   placeholder="Enter territory name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label text-muted small fw-semibold">Territory Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg border-2" id="code" name="code" 
                                   placeholder="e.g., NORTH-01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label text-muted small fw-semibold">Territory Type <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg border-2" id="type" name="type" required>
                                <option value="">Select territory type</option>
                                <option value="geographic">Geographic</option>
                                <option value="demographic">Demographic</option>
                                <option value="product">Product-based</option>
                                <option value="account">Account-based</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="manager_id" class="form-label text-muted small fw-semibold">Territory Manager</label>
                            <select class="form-select form-select-lg border-2" id="manager_id" name="manager_id">
                                <option value="">Select manager</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label text-muted small fw-semibold">Description</label>
                            <textarea class="form-control border-2" id="description" name="description" rows="3" 
                                      placeholder="Describe the territory coverage, focus areas, or special notes..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="target_revenue" class="form-label text-muted small fw-semibold">Target Revenue</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-2">$</span>
                                <input type="number" class="form-control form-control-lg border-2 border-start-0" 
                                       id="target_revenue" name="target_revenue" step="0.01" 
                                       placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="priority_level" class="form-label text-muted small fw-semibold">Priority Level</label>
                            <select class="form-select form-select-lg border-2" id="priority_level" name="priority_level">
                                <option value="low">Low Priority</option>
                                <option value="medium" selected>Medium Priority</option>
                                <option value="high">High Priority</option>
                                <option value="critical">Critical Priority</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Create Territory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Map View Modal -->
<div class="modal fade" id="mapViewModal" tabindex="-1" aria-labelledby="mapViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-bold" id="mapViewModalLabel">
                    <i class="fas fa-map me-2"></i>Territory Map View
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 70vh;">
                <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                    <div class="text-center">
                        <i class="fas fa-map fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Map Integration Coming Soon</h5>
                        <p class="text-muted">Interactive territory mapping will be available in the next update.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stats-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .territory-item {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }

    .territory-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1) !important;
    }

    .territory-avatar {
        flex-shrink: 0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .badge {
        border-radius: 8px;
        font-weight: 500;
    }

    .progress {
        border-radius: 6px;
        background-color: #f8f9fa;
    }

    .progress-bar {
        border-radius: 6px;
    }

    .card {
        border-radius: 12px;
    }

    .modal-content {
        border-radius: 16px;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .input-group-text {
        border-radius: 8px 0 0 8px;
    }

    .territory-card {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .territory-card:nth-child(1) { animation-delay: 0.1s; }
    .territory-card:nth-child(2) { animation-delay: 0.2s; }
    .territory-card:nth-child(3) { animation-delay: 0.3s; }
    .territory-card:nth-child(4) { animation-delay: 0.4s; }
    .territory-card:nth-child(5) { animation-delay: 0.5s; }
    .territory-card:nth-child(6) { animation-delay: 0.6s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .text-muted {
        color: #6c757d !important;
    }

    .fw-semibold {
        font-weight: 600 !important;
    }

    .bg-gradient {
        background: linear-gradient(135deg, var(--bs-bg-opacity, 1), rgba(255,255,255,0.1)) !important;
    }

    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
        }
        
        .territory-item {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search and Filter Functionality
    const searchInput = $('#searchInput');
    const typeFilter = $('#typeFilter');
    const statusFilter = $('#statusFilter');
    const performanceFilter = $('#performanceFilter');
    const clearFiltersBtn = $('#clearFilters, #clearFiltersEmpty');
    const emptyState = $('#emptyState');
    const territoriesGrid = $('#territoriesGrid');

    function filterTerritories() {
        const searchTerm = searchInput.val().toLowerCase();
        const selectedType = typeFilter.val();
        const selectedStatus = statusFilter.val();
        const selectedPerformance = performanceFilter.val();

        let visibleCount = 0;

        $('.territory-card').each(function() {
            const $card = $(this);
            const name = $card.data('name');
            const code = $card.data('code');
            const manager = $card.data('manager');
            const type = $card.data('type');
            const status = $card.data('status');
            const performance = parseFloat($card.data('performance'));

            // Search filter
            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                code.includes(searchTerm) || 
                manager.includes(searchTerm);

            // Type filter
            const matchesType = !selectedType || type === selectedType;

            // Status filter
            const matchesStatus = !selectedStatus || status === selectedStatus;

            // Performance filter
            let matchesPerformance = true;
            if (selectedPerformance === 'high') {
                matchesPerformance = performance > 80;
            } else if (selectedPerformance === 'medium') {
                matchesPerformance = performance >= 40 && performance <= 80;
            } else if (selectedPerformance === 'low') {
                matchesPerformance = performance < 40;
            }

            const shouldShow = matchesSearch && matchesType && matchesStatus && matchesPerformance;

            if (shouldShow) {
                $card.show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });

        // Toggle empty state
        if (visibleCount === 0) {
            emptyState.show();
            territoriesGrid.addClass('d-none');
        } else {
            emptyState.hide();
            territoriesGrid.removeClass('d-none');
        }
    }

    // Event listeners
    searchInput.on('input', filterTerritories);
    typeFilter.on('change', filterTerritories);
    statusFilter.on('change', filterTerritories);
    performanceFilter.on('change', filterTerritories);

    // Clear filters
    clearFiltersBtn.on('click', function() {
        searchInput.val('');
        typeFilter.val('');
        statusFilter.val('');
        performanceFilter.val('');
        filterTerritories();
    });

    // Enhanced modal animations
    $('.modal').on('show.bs.modal', function() {
        $(this).find('.modal-dialog').addClass('animate__animated animate__fadeInUp');
    });

    // Form validation enhancements
    $('form').on('submit', function(e) {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
        
        // Re-enable after 3 seconds as fallback
        setTimeout(() => {
            $submitBtn.prop('disabled', false).html($submitBtn.data('original-text') || 'Submit');
        }, 3000);
    });

    // Store original button text
    $('button[type="submit"]').each(function() {
        $(this).data('original-text', $(this).html());
    });

    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-generate territory code based on name
    $('#name').on('input', function() {
        const name = $(this).val();
        const code = name.toUpperCase()
            .replace(/[^A-Z0-9\s]/g, '')
            .replace(/\s+/g, '-')
            .substring(0, 10);
        $('#code').val(code);
    });
});
</script>
@endpush
@endsection
