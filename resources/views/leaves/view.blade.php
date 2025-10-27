@extends('layouts.main')

@section('content')
    <div class="container-fluid p-0">
        <h3 class="mb-4 fw-bolder">leave Details</h3>

        <div class="corner-3 bg-white shadow-sm p-3">
            <div class="row">
                @if ($isAdmin)
                    <div class="col-sm-6 mb-3">
                        <label class="form-label fw-bold">Name:</label>
                        {{ $leave->user->name }}
                    </div>
                @endif

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Leave Type:</label>
                    {{ $leave->type }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">@php
                        $dateText = $isOneDayLeave ? 'Date:' : 'Start Date:';
                    @endphp {{ $dateText }}</label>
                    {{ $leave->start_date }}
                </div>
                @if (!$isOneDayLeave)
                    <div class="col-sm-6 mb-3">
                        <label class="form-label fw-bold">End Date:</label>
                        {{ $leave->start_date }}
                    </div>
                @endif
                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Leave Reason:</label>
                    {{ $leave->request_reason ?? 'N/A' }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Status:</label>
                    {{ $leave->status }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Admin Remarks:</label>
                    {{ $leave->admin_remarks ?? 'N/A' }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Applied At:</label>
                    {{ $leave->created_at->format('Y-m-d') }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Status Updated At:</label>
                    {{ $leave->updated_at ? $leave->updated_at->format('Y-m-d') : 'N/A' }}
                </div>


            </div>

            <a href="{{ route('leaves.index') }}" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>

    <script>
        document.getElementById('password-toggle').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            const type = pwd.type === 'password' ? 'text' : 'password';
            pwd.type = type;
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
