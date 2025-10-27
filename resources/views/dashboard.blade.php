@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4 fw-bold">Dashboard</h3>

        <div class="row g-4">
            @role('admin')
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Total Employees</h6>
                        <h2 class="fw-bold text-primary">{{ $employeeCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Today's Leave Requests</h6>
                        <h2 class="fw-bold text-warning">{{ $todayLeaveCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Total Leave Requests</h6>
                        <h2 class="fw-bold text-dark">{{ $totalLeaveCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Accepted Leave Requests</h6>
                        <h2 class="fw-bold text-success">{{ $acceptedCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Rejected Leave Requests</h6>
                        <h2 class="fw-bold text-danger">{{ $rejectedCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Pending Leave Requests</h6>
                        <h2 class="fw-bold text-secondary">{{ $pendingCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3 position-relative">
                        <h6 class="text-muted">Allocated Leave</h6>
                        <h2 class="fw-bold text-info">{{ $allocatedLeave }}</h2>
                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2" data-bs-toggle="modal"
                            data-bs-target="#leaveSettingModal">
                            <i class="bi bi-gear"></i>
                        </button>
                    </div>
                </div>
                @elserole('employee')
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Total Leave Requests</h6>
                        <h2 class="fw-bold text-primary">{{ $totalLeaveCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Accepted Leave Requests</h6>
                        <h2 class="fw-bold text-success">{{ $acceptedCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Rejected Leave Requests</h6>
                        <h2 class="fw-bold text-danger">{{ $rejectedCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Pending Leave Requests</h6>
                        <h2 class="fw-bold text-warning">{{ $pendingCount }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h6 class="text-muted">Remaining Leave for this year</h6>
                        <h2 class="fw-bold text-dark">{{ $remainingLeaves }}</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center p-3 position-relative">
                        <h6 class="text-muted">Allocated Leave</h6>
                        <h2 class="fw-bold text-info">{{ $allocatedLeave }}</h2>
                    </div>
                </div>
            @endrole
        </div>
    </div>

    <div class="modal fade" id="leaveSettingModal" tabindex="-1" aria-labelledby="leaveSettingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="leaveSettingModalLabel">Update Allocated Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('leave-settings.update') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="allocated_days" class="form-label">Allocated Leaves</label>
                            <input type="number" min="0" name="allocated_days" id="allocated_days"
                                class="form-control" value="{{ $allocatedLeave }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
