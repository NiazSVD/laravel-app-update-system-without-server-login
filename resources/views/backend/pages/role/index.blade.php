@extends('backend.master')
@section('title', 'Softvence Food Rool Manage')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h3 class="card-title">Role List</h3>
            @can('role create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                   + Add Role
                </button>
            @endcan
        </div>

        <div class="card-content pt-4">
            <div class="table-responsive">
                <table class="table mb-0 table-lg" id="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td class="text-bold-500">{{ $loop->iteration }}</td>
                                <td class="text-bold-500">{{ Str::title($role->name) }}</td>
                                <td>
                                    <a href="{{ route('admin.roles.permissions.edit', $role->id) }}"
                                        class="btn btn-sm btn-info rounded-pill">
                                        Manage
                                    </a>
                                </td>
                                <td>
                                    @can('role edit')
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editRoleModal" data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->name }}">
                                            Edit
                                        </button>
                                    @endcan
                                    @can('role delete')
                                        <form id="deleteRoleForm{{ $role->id }}"
                                            action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Role Modal --}}
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="role_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="role_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="permissions" class="form-label">Assign Permissions</label>
                            <div class="form-group">
                                <select name="permissions[]" id="permissions" class="choices form-select multiple-remove"
                                    multiple="multiple">
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->id }}"
                                            {{ in_array($permission->id, old('permissions', [])) ? 'selected' : '' }}>
                                            {{ Str::title($permission->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit role --}}

    <!-- Edit Role Name Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_role_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_role_name" name="name" required>
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
        $(document).ready(function() {

            $('#editRoleModal').on('show.bs.modal', function(event) {
                console.log('dsf');
                var button = $(event.relatedTarget);

                var roleId = button.data('role-id');
                var roleName = button.data('role-name');

                console.log(roleId, roleName);

                var modal = $(this);
                modal.find('#edit_role_name').val(roleName);
                modal.find('#editRoleForm').attr('action', '/admin/roles/' + roleId);
            });

        });


        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This role will NOT be deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete!",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteRoleForm' + id).submit();
                }
            });
        }
    </script>
@endsection
