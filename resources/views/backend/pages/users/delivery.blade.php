@extends('backend.master')
@section('title', 'Softvence Food Delivery Man')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h3 class="card-title">Delivery Man List</h3>
            @can('user create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    + Add Delivery Man
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
                            <th>Phone</th> <!-- Changed -->
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td> <!-- Changed -->
                                <td>{{ $defaultRole }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal" data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}" data-user-phone="{{ $user->phone }}"
                                        data-user-role="{{ optional($user->roles->first())->id }}">
                                        Edit
                                    </button>

                                    @if ($user->id != 1)
                                        <form id="deleteUserForm{{ $user->id }}"
                                            action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $user->id }})">
                                                Delete
                                            </button>
                                        </form>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No delivery man found.</td>
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
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Delivery Man</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" required>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editUserForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Delivery Man</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="edit_user_name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" id="edit_user_phone" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assign Role</label>
                            <select name="role" id="edit_user_role" class="form-select" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ Str::title($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('show.bs.modal', '#editUserModal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#edit_user_name').val(button.data('user-name'));
            modal.find('#edit_user_phone').val(button.data('user-phone'));

            modal.find('#editUserForm').attr('action', '/admin/users/' + button.data('user-id'));
            modal.find('#edit_user_role').val(button.data('user-role')).trigger('change');
        });

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This user will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete!"
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('deleteUserForm' + id).submit();
            });
        }
    </script>
@endsection
