@extends('layouts.main')

@section('content')
    <div class="container-fluid p-0">

        <h3 class="mb-4 fw-bolder">{{ isset($user) ? 'Edit User' : 'Create User' }}</h3>

        <div class="corner-3 bg-white shadow-sm p-3">
            <form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">

                @csrf
                @if (isset($user))
                    @method('PATCH')
                @endif

                <div class="row">

                    <div class="col-sm-6">
                        <label class="form-label mt-2">Name</label>
                        <input type="text" name="name" class="form-control mb-3 @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name ?? '') }}" required>
                        @error('name')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label mt-2">Email</label>
                        <input type="email" name="email" class="form-control mb-3 @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email ?? '') }}" required>
                        @error('email')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label mt-2">Role</label>
                        <select name="role" class="form-select mb-3 @error('role') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            @foreach ($roles as $roleName)
                                <option value="{{ $roleName }}"
                                    {{ isset($user) && $user->roles->pluck('name')->contains($roleName) ? 'selected' : '' }}>
                                    {{ ucfirst($roleName) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label mt-2">Password</label>
                        <div class="input-group mb-3">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                value="{{ old('password', $user->pass ?? '') }}" required>
                            <button class="btn btn-outline-secondary" type="button" id="password-toggle">
                                <i class="fa fa-eye-slash"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-sm-6 mt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1"
                                {{ (isset($user) && $user->status) || !isset($user) ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusSwitch">Active</label>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <input type="submit" class="btn btn-primary mt-5 float-end col-4"
                            value="{{ isset($user) ? 'Update' : 'Create' }}">
                    </div>

                </div>
            </form>
        </div>

    </div>

    <script>
        document.getElementById('password-toggle').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
