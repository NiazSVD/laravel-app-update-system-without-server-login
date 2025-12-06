@extends('backend.master')
@section('title', 'Softvence Food Admin')
@section('content')

    <div class="page-content d-flex justify-content-center align-items-center text-dark mb-5">
        <section class="row g-4 w-100 d-flex justify-content-center">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="card-title">Food List</h3>
                    {{-- @can('user create') --}}
                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFoodModal">
                        + Add Food
                    </button>
                    {{-- @endcan --}}
                </div>

                <div class="card-content pt-4">
                    <div class="table-responsive">
                        <table class="table mb-0 table-lg" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Food Name</th>
                                    <th>Vendor</th>
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($get_foods as $key => $get_food)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $get_food->name }}</td>
                                        <td>{{ $get_food->get_user->name }}</td>
                                        <td>{{ $get_food->category->name }}</td>
                                        <td>
                                            @if ($get_food->image)
                                                <img src="{{ asset('storage/uploads/food/' . $get_food->image) }}"
                                                    alt="{{ $get_food->name }}"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                                                    class="food-image" data-image="{{ $get_food->image }}"
                                                    onerror="this.style.display='none'">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($get_food->description, 50) }}</td>
                                        <td>{{ $get_food->stock }}</td>
                                        <td>
                                            <span
                                                class="badge
                                               {{ trim($get_food->stock) == 0 ? 'bg-danger' : (trim($get_food->status) == 1 ? 'bg-success' : 'bg-danger') }}">
                                                {{ trim($get_food->stock) == 0 ? 'Stock Out' : (trim($get_food->status) == 1 ? 'Available' : 'Unavailable') }}
                                            </span>
                                        </td>


                                        <td>৳{{ number_format($get_food->price, 2) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editFoodModal" data-food-id="{{ $get_food->id }}"
                                                data-food-name="{{ $get_food->name }}"
                                                data-food-price="{{ $get_food->price }}"
                                                data-food-stock="{{ $get_food->stock }}"
                                                data-food-stock="{{ $get_food->stock }}"
                                                data-food-description="{{ $get_food->description }}"
                                                data-food-category="{{ $get_food->category_id }}"
                                                data-food-status="{{ $get_food->status }}"
                                                data-food-image="{{ $get_food->image }}">
                                                Edit
                                            </button>

                                            <form id="deleteFoodForm{{ $get_food->id }}"
                                                action="{{ route('admin.food.delete', $get_food->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $get_food->id }})">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No food items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Add Food Modal --}}
            <div class="modal fade" id="addFoodModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('admin.food.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Food</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-2">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach ($get_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Image <small class="text-muted">(Recommended: JPG, PNG,
                                            JPEG)</small></label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <small class="text-muted"></small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" name="price" required>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Stock</label>
                                        <input type="number" step="0.01" class="form-control" name="stock" required>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="2" required></textarea>
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

            {{-- Edit Food Modal --}}
            <div class="modal fade" id="editFoodModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="editFoodForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Food</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-2">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="edit_food_name"
                                        required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id" id="edit_food_category" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach ($get_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Image</label>
                                    <div id="current_image_container" class="mt-2">
                                        <img id="current_food_image" src="" alt="Current Food Image"
                                            style="max-width: 200px; max-height: 200px; display: none;"
                                            class="img-thumbnail">
                                        <div id="no_image_message" class="text-muted">No image available</div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_image"
                                            id="remove_image" value="1">
                                        <label class="form-check-label" for="remove_image">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Update Image <small class="text-muted">(Recommended: JPG,
                                            PNG, JPEG)</small></label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <small class="text-muted"></small>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status" id="edit_food_status" required>
                                            <option value="1">Available</option>
                                            <option value="0">Unavailable</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" name="price"
                                            id="edit_food_price" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Stock</label>
                                        <input type="number" step="0.01" class="form-control" name="stock"
                                            id="edit_food_stock" required>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="edit_food_description" rows="3" required></textarea>
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

        $(document).ready(function() {
            // Edit modal show event
            $(document).on('show.bs.modal', '#editFoodModal', function(event) {
                var button = $(event.relatedTarget);

                var foodId = button.data('food-id') || button.data('foodId');
                var foodName = button.data('food-name') || button.data('foodName') || '';
                var foodPrice = button.data('food-price') || button.data('foodPrice') || '';
                var foodStock = button.data('food-stock') || button.data('foodStock') || '';
                var foodDescription = button.data('food-description') || button.data('foodDescription') || '';
                var foodCategory = button.data('food-category') || button.data('foodCategory') || '';
                var foodStatus = button.data('food-status') ?? button.data('foodStatus') ?? '1';
                var foodImage = button.data('food-image') || button.data('foodImage') || '';

                var modal = $(this);

                // Set form values
                modal.find('#edit_food_name').val(foodName);
                modal.find('#edit_food_price').val(foodPrice);
                modal.find('#edit_food_stock').val(foodStock);
                modal.find('#edit_food_description').val(foodDescription);
                modal.find('#edit_food_category').val(foodCategory);
                modal.find('#edit_food_status').val(foodStatus);

                // Set form action
                if (foodId) {
                    modal.find('#editFoodForm').attr('action', '/admin/food/update/' + foodId);
                } else {
                    modal.find('#editFoodForm').attr('action', '#');
                }

                // Store original image for reset
                modal.find('#current_image_container').data('original-image', foodImage);

                // Display current image
                displayCurrentImage(modal, foodImage);

                // Reset remove image checkbox
                modal.find('#remove_image').prop('checked', false);

                // Initialize image preview for edit modal
                initializeEditImagePreview(modal);
            });

            // Edit modal hidden event
            $(document).on('hidden.bs.modal', '#editFoodModal', function() {
                var modal = $(this);
                modal.find('form')[0].reset();
                modal.find('#editFoodForm').attr('action', '#');

                // Reset to original image
                var originalImage = modal.find('#current_image_container').data('original-image');
                displayCurrentImage(modal, originalImage);

                modal.find('#remove_image').prop('checked', false);
            });

            // Remove image checkbox change event
            $(document).on('change', '#remove_image', function() {
                var modal = $(this).closest('.modal');
                if ($(this).is(':checked')) {
                    modal.find('#current_food_image').hide();
                    modal.find('#no_image_message').text('Image will be removed after update').show();
                } else {
                    // Show current image (could be original or new preview)
                    var currentImage = modal.find('#current_food_image').attr('src');
                    if (currentImage && !currentImage.includes('blob:')) {
                        modal.find('#current_food_image').show();
                        modal.find('#no_image_message').hide();
                    } else {
                        var originalImage = modal.find('#current_image_container').data('original-image');
                        displayCurrentImage(modal, originalImage);
                    }
                }
            });

            // Initialize image preview for add modal
            initializeAddImagePreview();
        });

        // Function to display image in current image container
        function displayCurrentImage(modal, foodImage) {
            var currentImage = modal.find('#current_food_image');
            var noImageMessage = modal.find('#no_image_message');

            if (foodImage) {
                var imageUrl = "{{ asset('storage/uploads/food/') }}/" + foodImage;
                currentImage.attr('src', imageUrl).show();
                noImageMessage.hide();
            } else {
                currentImage.hide();
                noImageMessage.text('No image available').show();
            }
        }

        // Function to initialize image preview for edit modal
        function initializeEditImagePreview(modal) {
            const imageInput = modal.find('input[name="image"]')[0];

            // Add change event listener for image input
            $(imageInput).off('change').on('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    // Validate file type
                    if (!file.type.match('image.*')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File',
                            text: 'Please select a valid image file (JPEG, PNG, JPG, GIF)',
                        });
                        $(this).val('');
                        return;
                    }

                    // Validate file size (2MB limit)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'Image size should be less than 2MB',
                        });
                        $(this).val('');
                        return;
                    }

                    // Create preview and display in current image container
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        var currentImage = modal.find('#current_food_image');
                        var noImageMessage = modal.find('#no_image_message');

                        // Show new image in current image container
                        currentImage.attr('src', e.target.result)
                            .css({
                                'border': '2px solid #28a745',
                                'box-shadow': '0 0 10px rgba(40, 167, 69, 0.3)'
                            })
                            .show();
                        noImageMessage.hide();

                        // Add replacement indicator
                        if (!modal.find('.replacement-indicator').length) {
                            modal.find('#current_image_container').append(`
                                <div class="replacement-indicator mt-2">
                                    <small class="text-success">
                                        <i class="bi bi-check-circle"></i>
                                        <strong>New Image Selected:</strong> ${file.name}
                                    </small>
                                    <br>
                                    <small class="text-warning">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Will replace the original image after update
                                    </small>
                                </div>
                            `);
                        } else {
                            modal.find('.replacement-indicator').html(`
                                <small class="text-success">
                                    <i class="bi bi-check-circle"></i>
                                    <strong>New Image Selected:</strong> ${file.name}
                                </small>
                                <br>
                                <small class="text-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Will replace the original image after update
                                </small>
                            `);
                        }
                    };
                    reader.readAsDataURL(file);

                    // Uncheck remove image when new image is selected
                    modal.find('#remove_image').prop('checked', false);
                } else {
                    // If no file selected, reset to original image
                    var originalImage = modal.find('#current_image_container').data('original-image');
                    displayCurrentImage(modal, originalImage);
                    modal.find('.replacement-indicator').remove();
                }
            });
        }

        // Function to initialize image preview for add modal
        function initializeAddImagePreview() {
            const imageInput = document.querySelector('#addFoodModal input[name="image"]');

            if (imageInput) {
                const previewContainer = document.createElement('div');
                previewContainer.className = 'add-image-preview-container mt-2';
                previewContainer.style.display = 'none';
                imageInput.parentNode.appendChild(previewContainer);

                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    previewContainer.innerHTML = '';
                    previewContainer.style.display = 'none';

                    if (file) {
                        // Validate file type
                        if (!file.type.match('image.*')) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid File',
                                text: 'Please select a valid image file (JPEG, PNG, JPG, GIF)',
                            });
                            imageInput.value = '';
                            return;
                        }

                        // Validate file size (2MB limit)
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Image size should be less than 2MB',
                            });
                            imageInput.value = '';
                            return;
                        }

                        // Create preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewContainer.style.display = 'block';
                            previewContainer.innerHTML = `
                                <div class="image-preview-wrapper">
                                    <img src="${e.target.result}"
                                        alt="Preview"
                                        class="img-thumbnail"
                                        style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                    <div class="mt-1">
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i>
                                            Image selected: ${file.name}
                                        </small>
                                        <br>
                                        <small class="text-muted">Size: ${(file.size / 1024).toFixed(2)} KB</small>
                                    </div>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        // Delete confirmation function
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This food item will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete!",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteFoodForm' + id).submit();
                }
            });
        }
    </script>

    <style>
        .replacement-indicator {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
        }

        .bi-check-circle {
            color: #28a745;
        }

        .bi-exclamation-triangle {
            color: #ffc107;
        }
    </style>
@endsection
