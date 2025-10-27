@extends('layouts.main')

@section('content')
    <div class="container-fluid p-0">
        <h3 class="mb-4 fw-bolder">User Details</h3>

        <div class="corner-3 bg-white shadow-sm p-3">
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Name:</label>
                    {{ $user->name }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Email:</label>
                    {{ $user->email }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Role:</label> {{ $user->roles->pluck('name')->first() ?? '-' }}
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Status:</label>{{ $user->status ? 'Active' : 'Inactive' }}
                </div>

                @if ($user->type == \App\Enums\UserType::EMPLOYEE)
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card text-center p-3 shadow-sm">
                                <h6 class="text-muted mb-1">Allocated Leaves({{ now()->year }})</h6>
                                <h4 class="text-info">{{ $allocatedLeave ?? 0 }}</h4>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center p-3 shadow-sm">
                                <h6 class="text-muted mb-1">Used Leaves</h6>
                                <h4 class="text-danger">{{ $usedLeaves ?? 0 }}</h4>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center p-3 shadow-sm">
                                <h6 class="text-muted mb-1">Remaining Leaves</h6>
                                <h4 class="text-success">{{ $remainingLeaves ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                @endif


                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" value="{{ $user->pass ?? '' }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="password-toggle">
                            <i class="fa fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back</a>
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
