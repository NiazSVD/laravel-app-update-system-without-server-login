@extends('backend.master')

@section('title', 'Food List')



@section('content')
    <div class="page-content d-flex justify-content-center align-items-center text-dark mb-5">
        <section class="row g-4 w-100 d-flex justify-content-center">
            <!-- FOOD LIST -->
            <div class="container mt-5">

                <h4 class="fw-bold mb-5 text-center">আজকের খাবারের তালিকা</h4>

                <!-- Category Filters (Buttons) -->
                <div class="d-flex justify-content-center mb-4 gap-3">
                    @foreach ($categories as $category)
                        @php
                            $isActiveCategory = isset($defaultCategoryId) && $defaultCategoryId == $category->id;
                        @endphp

                        <button
                            class="btn btn-outline-primary filter-btn
                                {{ $category->is_disabled ? 'disabled btn-secondary text-light' : '' }}
                                {{ $isActiveCategory ? 'active' : '' }}"
                            data-filter=".category-{{ $category->id }}"
                            {{ $category->is_disabled ? 'disabled' : '' }}>

                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>



                <!-- Food List (MixItUp Grid) -->
                <div class="row food-list" id="food-list">
                    @foreach ($categories as $category)
                        @foreach ($category->foods as $food)
                            <div class="col-md-3 mix category-{{ $category->id }}">
                                <div class="food-card card card-body border mb-4">

                                    <img class="food-img img-fluid rounded"
                                        style="height: 200px; width: 100%; object-fit: cover;"
                                        src="{{ asset('storage/uploads/food/' . $food->image) }}">

                                    <h6 class="mt-3 text-dark font-weight-bold mb-1">{{ $food->name }}</h6>
                                    <small class="text-secondary d-block pb-2">
                                        {{ App\Models\User::where('id', $food->vendor_id)->first()->name ?? 'Swopnil Hotel' }}
                                    </small>

                                    <p class="text-muted mb-3">
                                        {{ Str::limit($food->description, 50) }}
                                    </p>

                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold text-danger">Price:</span>
                                        <span class="price ms-2 fw-bold text-success" data-base="{{ $food->price }}">
                                            {{ $food->price }}
                                        </span>
                                        <span class="ms-2">BDT</span>

                                        <span class="ms-5 fw-blood text-black">Stock:</span>
                                        <span class="stock fw-bold ms-2
                                            {{ $food->stock < 10 ? 'text-danger' : 'text-success' }}"
                                            data-base="{{ $food->stock }}">
                                            {{ $food->stock }}
                                        </span>
                                    </div>


                                    <!-- Quantity -->
                                    <div class="d-flex align-items-center mt-3">
                                        <button class="qty-btn minus btn btn-secondary text-white"
                                            style="width: 30px; height: 30px; padding: 0; font-size: 20px;">−</button>
                                        <span class="mx-3 qty fs-5 fw-bold">1</span>
                                        <button class="qty-btn plus btn btn-success text-white"
                                            style="width: 30px; height: 30px; padding: 0; font-size: 20px;">+</button>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex gap-2 mt-3">
                                        @if ($food->stock > 0)
                                            <button class="btn btn-primary w-50 cartBtn" data-id="{{ $food->id }}">
                                                Add to Cart
                                            </button>
                                        @else
                                            <button class="btn btn-primary w-50 cartBtn" data-id="">
                                                Add to Cart
                                            </button>
                                        @endif
                                        @if ($food->stock > 0)
                                            <button class="btn btn-success w-50 orderBtn" data-id="{{ $food->id }}"
                                                data-qty="1">
                                                Order
                                            </button>
                                        @else
                                            <button class="btn btn-danger w-50" disabled>
                                                Stock Out
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
    </div>




@endsection

@section('script')



    <script>
        // DEFAULT CATEGORY FROM CONTROLLER
        var defaultCategory = ".category-{{ $defaultCategoryId }}";

        var mixer = mixitup('#food-list', {
            selectors: {
                target: '.mix'
            },
            load: {
                filter: defaultCategory   // AUTO SHOW DEFAULT CATEGORY
            },
            animation: {
                effects: 'fade scale',
                duration: 500,
                easing: 'ease-in-out'
            }
        });

        // Filter button click
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                var filterValue = this.getAttribute('data-filter');
                mixer.filter(filterValue);

                // Active class update
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            $(".orderBtn").click(function() {

                let foodId = $(this).data("id");
                let qty = $(this).closest(".food-card").find(".qty").text();

                // AJAX add to cart
                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        food_id: foodId,
                        quantity: qty
                    },
                    success: function(response) {
                        // Redirect to cart page after successful add
                        window.location.href = "{{ route('cart.index') }}";
                    },
                    error: function(xhr) {
                        alert("Error adding to cart!");
                    }
                });

            });

        });
    </script>



    <script>
        // Quantity + Price Update
        document.querySelectorAll('.food-card').forEach(card => {

            let qty = card.querySelector('.qty');
            let price = card.querySelector('.price');
            let base = parseInt(price.dataset.base);

            card.querySelector('.plus').onclick = () => {
                qty.innerText = parseInt(qty.innerText) + 1;
                price.innerText = base * parseInt(qty.innerText);
            };

            card.querySelector('.minus').onclick = () => {
                if (parseInt(qty.innerText) > 1) {
                    qty.innerText = parseInt(qty.innerText) - 1;
                    price.innerText = base * parseInt(qty.innerText);
                }
            };
        });


        // AJAX Add to Cart
        $(".cartBtn").click(function() {

            let foodId = $(this).data("id");
            let qty = $(this).closest(".food-card").find(".qty").text();

            $.ajax({
                url: "{{ route('cart.add') }}",
                type: "POST",
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    food_id: foodId,
                    quantity: qty
                },

                success: function(response) {
                    // Update cart count
                    $("#cartCount").text(response.cart_count);

                    // Toastify success
                    Toastify({
                        text: response.message || "Added to cart successfully!",
                        duration: 1500,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#4fbe87",
                        close: true
                    }).showToast();
                },
                error: function(xhr) {
                    Toastify({
                        text: xhr.responseJSON?.message || "Error adding to cart!",
                        duration: 2000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#f3616d",
                        close: true
                    }).showToast();
                }
            });
        });
    </script>




@endsection
