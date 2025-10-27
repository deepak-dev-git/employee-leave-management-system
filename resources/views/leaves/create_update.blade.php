@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4 fw-bolder">{{ isset($leave) ? 'Update Leave' : 'Apply Leave' }}</h3>

        <div class="corner-3 bg-white shadow-sm p-3">
            <form method="POST" action="{{ isset($leave) ? route('leaves.update', $leave->id) : route('leaves.store') }}">
                @csrf
                @if (isset($leave))
                    @method('PATCH')
                @endif

                <div class="row">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-sm-6">
                        <label class="form-label mt-2">Leave Type</label>
                        <select id="leaveType" name="type" class="form-select"
                            {{ isset($leave) && $isAdmin ? 'disabled' : '' }} required>
                            <option value="">Select Leave Type</option>
                            @foreach (\App\Enums\LeaveType::cases() as $type)
                                <option value="{{ $type->value }}"
                                    {{ old('type', $leave->type ?? '') === $type->value ? 'selected' : '' }}>
                                    {{ ucfirst($type->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label id="dateLabel" class="form-label mt-2">
                            {{ $isAdmin && ($isOneDayLeave ?? false) ? 'Date' : 'Start Date' }}
                        </label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ old('start_date', $leave->start_date ?? '') }}"
                            {{ isset($leave) && $isAdmin ? 'readonly' : 'required' }}>
                    </div>

                    <div class="col-sm-6" id="endDateWrapper"
                        style="{{ $isOneDayLeave ?? false ? 'display:none;' : '' }}">
                        <label class="form-label mt-2">End Date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ old('end_date', $leave->end_date ?? '') }}"
                            {{ isset($leave) && $isAdmin ? 'readonly' : '' }}>
                    </div>

                    <div class="col-sm-12 mt-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="oneDayLeave" name="is_one_day"
                                value="1" {{ $isOneDayLeave ?? false ? 'checked' : '' }}
                                {{ isset($leave) && $isAdmin ? 'disabled' : '' }}>
                            <label class="form-check-label" for="oneDayLeave">One Day Leave</label>
                        </div>
                    </div>

                    <div class="col-sm-12 mt-2" id="reasonContainer"
                        style="{{ (!isset($leave) && old('type') !== 'others' && !old('request_reason')) || (isset($leave) && empty($leave->request_reason) && ($leave->type ?? '') !== 'others') ? 'display:none;' : '' }}">
                        <label class="form-label">Reason</label>
                        <textarea name="request_reason" class="form-control" {{ isset($leave) && $isAdmin ? 'readonly' : '' }}>{{ old('request_reason', $leave->request_reason ?? '') }}</textarea>
                    </div>

                    @if (isset($leave) && $isAdmin)
                        <div class="col-sm-6 mt-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="statusSelect" class="form-select" required>
                                <option value="">Select Leave Status</option>
                                @foreach (\App\Enums\LeaveStatus::cases() as $type)
                                    <option value="{{ $type->value }}"
                                        {{ old('status', $leave->status ?? '') === $type->value ? 'selected' : '' }}>
                                        {{ ucfirst($type->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 mt-3" id="adminRemarksWrapper"
                            style="{{ old('status', $leave->status ?? '') === 'rejected' ? '' : 'display:none;' }}">
                            <label class="form-label">Admin Remarks</label>
                            <textarea name="admin_remarks" class="form-control">{{ old('admin_remarks', $leave->admin_remarks ?? '') }}</textarea>
                        </div>
                    @endif

                    <div class="col-sm-6 mt-4">
                        <input type="submit" class="btn btn-primary float-end col-4"
                            value="{{ isset($leave) && $isAdmin ? 'Update' : 'Submit' }}">
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leaveType = document.getElementById('leaveType');
            const reasonContainer = document.getElementById('reasonContainer');
            const oneDayCheckbox = document.getElementById('oneDayLeave');
            const endDateWrapper = document.getElementById('endDateWrapper');
            const dateLabel = document.getElementById('dateLabel');
            const statusSelect = document.getElementById('statusSelect');
            const adminRemarksWrapper = document.getElementById('adminRemarksWrapper');

            const toggleReason = () => {
                if (!reasonContainer) return;
                if (leaveType && leaveType.value === "Others") {
                    reasonContainer.style.display = '';
                } else if (reasonContainer.querySelector('textarea').value.trim() !== '') {
                    reasonContainer.style.display = '';
                } else {
                    reasonContainer.style.display = 'none';
                }
            };
            toggleReason();
            if (leaveType) leaveType.addEventListener('change', toggleReason);

            const toggleOneDayLeave = () => {
                if (!oneDayCheckbox) return;
                if (oneDayCheckbox.checked) {
                    if (endDateWrapper) endDateWrapper.style.display = 'none';
                    if (dateLabel) dateLabel.innerText = 'Date';
                } else {
                    if (endDateWrapper) endDateWrapper.style.display = 'block';
                    if (dateLabel) dateLabel.innerText = 'Start Date';
                }
            };
            toggleOneDayLeave();
            if (oneDayCheckbox && !oneDayCheckbox.disabled) {
                oneDayCheckbox.addEventListener('change', toggleOneDayLeave);
            }

            const toggleAdminRemarks = () => {
                console.log(statusSelect.value);

                if (!statusSelect || !adminRemarksWrapper) return;
                if (statusSelect.value === 'Rejected') {
                    adminRemarksWrapper.style.display = '';
                } else {
                    adminRemarksWrapper.style.display = 'none';
                }
            };
            toggleAdminRemarks();
            if (statusSelect) statusSelect.addEventListener('change', toggleAdminRemarks);
        });
    </script>
@endsection
