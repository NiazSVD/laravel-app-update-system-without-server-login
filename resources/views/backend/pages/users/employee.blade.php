@extends('backend.master')
@section('title', 'Softvence Food Employee')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h3 class="card-title">Employee List</h3>
            @can('user create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    + Add Employee
                </button>
            @endcan
        </div>

        <div class="card-content pt-4">
            <div class="table-responsive">
                <table class="table mb-0 table-lg" id="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Team</th>
                            <th>Employee Code</th>
                            <th>Seat</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ optional($user->team)->name }}</td>
                                <td>{{ $user->employee_code }}</td>
                                <td>F:{{ $user->floor }} | R:{{ $user->row }} | S:{{ $user->seat_number }}</td>
                                <td>{{ optional($user->roles->first())->name }}</td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal" data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}" data-user-phone="{{ $user->phone }}"
                                        data-user-role="{{ optional($user->roles->first())->id }}"
                                        data-user-team="{{ $user->team_id }}" data-user-code="{{ $user->employee_code }}"
                                        data-user-floor="{{ $user->floor }}" data-user-row="{{ $user->row }}"
                                        data-user-seat="{{ $user->seat_number }}">
                                        Edit
                                    </button>

                                    <form id="deleteUserForm{{ $user->id }}"
                                        action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $user->id }})">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No employee found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add User Modal --}}
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.users.store') }}" method="POST">@csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Team</label>
                                <select class="form-select" name="team_id">
                                    <option value="">-- Select Team --</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Employee Code</label>
                                <input type="text" class="form-control" name="employee_code">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Floor</label>
                                <input type="number" class="form-control" name="floor">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Row</label>
                                <input type="number" class="form-control" name="row">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Seat No</label>
                                <input type="number" class="form-control" name="seat_number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <input type="hidden" name="role" value="{{ $defaultRole }}">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editUserForm" method="POST">@csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" id="edit_user_name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="phone" id="edit_user_phone" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Team</label>
                                <select class="form-select" name="team_id" id="edit_user_team">
                                    <option value="">-- Select Team --</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Employee Code</label>
                                <input type="text" class="form-control" name="employee_code" id="edit_user_code">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Floor</label>
                                <input type="number" class="form-control" name="floor" id="edit_user_floor">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Row</label>
                                <input type="number" class="form-control" name="row" id="edit_user_row">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Seat No</label>
                                <input type="number" class="form-control" name="seat_number" id="edit_user_seat">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Assign Role</label>
                            <select class="form-select" name="role" id="edit_user_role" required>
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ Str::title($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).on('show.bs.modal', '#editUserModal', function(event) {
            var b = $(event.relatedTarget);

            $('#editUserForm').attr('action', '/admin/users/' + b.data('user-id'));
            $('#edit_user_name').val(b.data('user-name'));
            $('#edit_user_phone').val(b.data('user-phone'));
            $('#edit_user_role').val(b.data('user-role'));

            $('#edit_user_team').val(b.data('user-team'));
            $('#edit_user_code').val(b.data('user-code'));

            $('#edit_user_floor').val(b.data('user-floor'));
            $('#edit_user_row').val(b.data('user-row'));
            $('#edit_user_seat').val(b.data('user-seat'));
        });

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This user will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete!"
            }).then(r => {
                if (r.isConfirmed) document.getElementById('deleteUserForm' + id).submit();
            });
        }
    </script>
@endsection
