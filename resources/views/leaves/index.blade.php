@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <h2>Leaves</h2>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('leaves.export', ['format' => 'excel']) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
                <a href="{{ route('leaves.export', ['format' => 'pdf']) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>

                @if (!$isAdmin)
                    <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Leave
                    </a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('leaves.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}"
                                        {{ request('status') == $status->value ? 'selected' : '' }}>
                                        {{ ucfirst($status->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Leave Type</label>
                            <select name="type" class="form-select">
                                <option value="">All</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->value }}"
                                        {{ request('type') == $type->value ? 'selected' : '' }}>
                                        {{ ucfirst($type->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">From</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                        </div>

                        @if ($isAdmin)
                            <div class="col-md-3">
                                <label class="form-label">Employee</label>
                                <input type="text" name="employee" value="{{ request('employee') }}"
                                    class="form-control" placeholder="Search by name">
                            </div>
                        @endif

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('leaves.index') }}" class="btn btn-secondary ms-2">Clear</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="leavesTable">
                        <thead class="table-light">
                            <tr>
                                @if ($isAdmin)
                                    <th>Employee Name</th>
                                @endif
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    @if ($isAdmin)
                                        <td>{{ $leave->user->name }}</td>
                                    @endif
                                    <td>{{ $leave->type }}</td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>{{ $leave->status }}</td>
                                    <td>{{ $leave->created_at->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('leaves.show', $leave->id) }}" class="btn btn-sm btn-info"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if ($isAdmin)
                                            <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $leaves->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_content')
@endpush
