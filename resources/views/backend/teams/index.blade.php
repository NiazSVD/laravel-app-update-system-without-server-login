@extends('backend.master')
@section('title', 'Softvence Food Team')
@section('content')

    <div class="page-content d-flex justify-content-center align-items-center text-dark mb-5">
        <section class="row g-4 w-100 d-flex justify-content-center">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="card-title">Team List</h3>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                        + Add Team
                    </button>
                </div>

                <div class="card-content pt-4">
                    <div class="table-responsive">
                        <table class="table mb-0 table-lg" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Note</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($teams as $team)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $team->name }}</td>
                                        <td>{{ $team->note }}</td>

                                        <td>
                                            {{-- Edit Button --}}
                                            <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editTeamModal"
                                                data-id="{{ $team->id }}"
                                                data-name="{{ $team->name }}"
                                                data-note="{{ $team->note }}">
                                                Edit
                                            </button>

                                            {{-- Delete Button --}}
                                            <form id="deleteTeamForm{{ $team->id }}"
                                                action="{{ route('admin.team.delete', $team->id) }}"
                                                method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $team->id }})">
                                                    Delete
                                                </button>
                                            </form>

                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No teams found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>


            {{-- ==================================================== --}}
            {{-- Add Team Modal --}}
            {{-- ==================================================== --}}
            <div class="modal fade" id="addTeamModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form action="{{ route('admin.team.store') }}" method="POST">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title">Add New Team</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>


            {{-- ==================================================== --}}
            {{-- Edit Team Modal --}}
            {{-- ==================================================== --}}
            <div class="modal fade" id="editTeamModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form id="editTeamForm" method="POST">
                            @csrf
                            @method('POST')

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Team</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="edit_team_name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <textarea name="note" id="edit_team_note" class="form-control"></textarea>
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
        </section>
    </div>

@endsection



@section('script')
<script>
    // Fill Edit Modal
    $(document).on("show.bs.modal", "#editTeamModal", function(event) {
        var button = $(event.relatedTarget);

        var id = button.data("id");
        var name = button.data("name");
        var note = button.data("note");

        var modal = $(this);

        modal.find("#edit_team_name").val(name);
        modal.find("#edit_team_note").val(note);

        // Set dynamic update route
        modal.find("#editTeamForm").attr("action", "/admin/team/update/" + id);
    });

    // Reset modal
    $(document).on("hidden.bs.modal", "#editTeamModal", function() {
        $(this).find("form")[0].reset();
        $("#editTeamForm").attr("action", "#");
    });


    // Delete Confirmation
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This team will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete!",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("deleteTeamForm" + id).submit();
            }
        });
    }
</script>


@endsection
