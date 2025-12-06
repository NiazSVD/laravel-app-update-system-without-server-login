@extends('backend.master')

@section('title', 'Food List')

@section('content')



    <!-- FOOD LIST -->
    <div class="container mt-5">
        <h3 class="text-center fw-bold mb-4">Today’s Menu</h3>

        <div class="row g-4">

            <!-- Example Item -->
            @foreach ($foods as $food)
                <div class="col-md-3">
                    <div class="food-card card card-body">


                        <img class="food-img img-fluid" src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092">

                        <h5 class="mt-3">{{ $food->name }}</h5>
                        <p class="text-muted">{{ $food->description }}</p>

                        <span class="fw-bold">Price:
                            <span class="price" data-base="{{ $food->price }}">{{ $food->price }}</span> BDT
                        </span>

                        <!-- Quantity -->
                        <div class="d-flex align-items-center mt-3">
                            <button class="qty-btn minus "
                                style="background-color: rgb(207, 75, 75); color:white">−</button>
                            <span class="mx-3 qty fs-5 fw-bold">1</span>
                            <button class="qty-btn plus" style="background-color: green; color:white">+</button>
                        </div>


                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-3">

                            <!-- ADD TO CART -->
                            <button class="btn btn-primary w-50 cartBtn" data-id="{{ $food->id }}">
                                Add to Cart
                            </button>

                            <!-- ORDER -->
                            <button class="btn btn-success w-50 orderBtn" data-id="{{ $food->id }}" data-qty="1">
                                Order
                            </button>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    </div>

@endsection

@section('script')

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
                // success: function(response) {
                //     alert(response.message);
                //     $("#cartCount").text(response.cart_count);
                // },
                // error: function(xhr) {
                //     console.log(xhr.responseText);
                //     alert("Error adding to cart!");
                // }

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
