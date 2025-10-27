@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Users</h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add User
            </a>
        </div>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
            <div id="statusToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="2000">
                <div class="d-flex">
                    <div class="toast-body" id="toastMessage">
                        Status updated successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this user?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="" {{ request('status') === null ? 'selected' : '' }}>All</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="" {{ request('type') === null ? 'selected' : '' }}>All</option>
                                <option value="Admin" {{ request('type') === 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Employee" {{ request('type') === 'Employee' ? 'selected' : '' }}>Employee
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" value="{{ request('username') }}" class="form-control"
                                placeholder="Search by name">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Clear</a>
                        </div>
                    </div>
                </form>


                <table class="table table-hover align-middle" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>User Type</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->type }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status" type="checkbox"
                                            data-id="{{ $user->id }}" {{ $user->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info"
                                        title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger deleteBtn"
                                            data-id="{{ $user->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('footer_content')
    <script>
        document.querySelectorAll('.toggle-status').forEach(el => {
            el.addEventListener('change', function() {
                const id = this.dataset.id;
                const status = this.checked ? 1 : 0;

                fetch(`/users/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const toastEl = document.getElementById('statusToast');
                            const toastMessage = document.getElementById('toastMessage');
                            toastMessage.innerText = status ? 'user Enabled' : 'user Disabled';

                            const toast = new bootstrap.Toast(toastEl);
                            toast.show();
                        }
                    })
                    .catch(err => console.error(err));
            });
        });
        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const form = document.getElementById('deleteForm');
                form.action = `/users/${id}`;

                // Show modal
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });
    </script>
@endpush
