@extends('backend.master')
@section('title', 'Softvence Food Categories')
@section('content')

    <div class="page-content d-flex justify-content-center align-items-center text-dark mb-5">
        <section class="row g-4 w-100 d-flex justify-content-center">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="card-title">Category List</h3>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        + Add Category
                    </button>
                </div>

                <div class="card-content pt-4">
                    <div class="table-responsive">
                        <table class="table mb-0 table-lg" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <!-- <th>Slug</th> -->
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($category->image)
                                                <img src="{{ asset($category->image) }}" width="45" height="45">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ Str::limit($category->description, 40) }}</td>
                                        <!-- <td>{{ $category->slug }}</td> -->
                                        <td>{{ \Carbon\Carbon::parse($category->start_time)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($category->end_time)->format('h:i A') }}</td>


                                        <td>
                                            @if($category->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>

                                        <td>

                                            {{-- Edit Button --}}
                                            <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-desc="{{ $category->description }}"
                                                data-start="{{ $category->start_time}}"
                                                data-end="{{ $category->end_time}}"
                                                data-status="{{ $category->status }}">
                                                Edit
                                            </button>

                                            {{-- Delete Button --}}
                                            <form id="deleteCatForm{{ $category->id }}"
                                                action="{{ route('admin.category.delete', $category->id) }}"
                                                method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $category->id }})">
                                                    Delete
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty

                                    <tr>
                                        <td colspan="7" class="text-center">No categories found.</td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>


            {{-- ========================== --}}
            {{-- Add Category Modal --}}
            {{-- ========================== --}}
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title">Add New Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>

                                <!-- <div class="mb-3">
                                    <label class="form-label">Slug (optional)</label>
                                    <input type="text" class="form-control" name="slug">
                                </div> -->

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Image <small class="text-muted">(Recommended: JPG, PNG, JPEG)</small></label>
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Start time</label>
                                    <input type="time" class="form-control" name="start_time">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">End time</label>
                                    <input type="time" class="form-control" name="end_time">
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
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



            {{-- ========================== --}}
            {{-- Edit Category Modal --}}
            {{-- ========================== --}}
            <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="edit_cat_name" required>
                                </div>

                                <!-- <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="edit_cat_slug">
                                </div> -->

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="edit_cat_desc"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">New Image (optional)<small class="text-muted">(Recommended: JPG, PNG, JPEG)</small></label>
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Start time</label>
                                    <input type="time" class="form-control" name="start_time" id="edit_cat_start">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">End time</label>
                                    <input type="time" class="form-control" name="end_time" id="edit_cat_end">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_cat_status" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
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
         </section>
    </div>

@endsection



@section('script')
<script>

// Fill Edit Modal
$(document).on("show.bs.modal", "#editCategoryModal", function(event) {

    var button = $(event.relatedTarget);

    var id = button.data("id");
    var name = button.data("name");
    var slug = button.data("slug");
    var desc = button.data("desc");
    var start = button.data("start");
    var end = button.data('end');
    var status = button.data("status");

    var modal = $(this);

    modal.find("#edit_cat_name").val(name);
    // modal.find("#edit_cat_slug").val(slug);
    modal.find("#edit_cat_desc").val(desc);
    modal.find("#edit_cat_start").val(start);
    modal.find("#edit_cat_end").val(end);
    modal.find("#edit_cat_status").val(status);

    modal.find("#editCategoryForm").attr("action", "/admin/category/update/" + id);
});


// Reset modal
$(document).on("hidden.bs.modal", "#editCategoryModal", function() {
    $(this).find("form")[0].reset();
    $("#editCategoryForm").attr("action", "#");
});


// Delete Confirm
function confirmDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This category will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete!"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("deleteCatForm" + id).submit();
        }
    });
}

</script>
@endsection
