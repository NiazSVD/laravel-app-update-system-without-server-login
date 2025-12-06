@extends('backend.master')

@section('title', 'Cart')

@section('content')

    <style>
        .qty-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            cursor: pointer;
        }

        .qty-box {
            width: 45px;
            text-align: center;
        }

        #checkoutSection {
            display: none;
            margin-top: 30px;
        }
    </style>

    <div class="page-content d-flex justify-content-center align-items-center text-dark mb-5">
        <section class="row g-4 w-100 d-flex justify-content-center">
            <div class="col-12">
                <div class="card-content">
                    <div class="card-body">
                        @if ($cartItems->count() == 0)

                            <div class="alert alert-warning text-center">
                                Your cart is empty.
                            </div>
                        @else
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h3 class="mb-4">My Cart</h3>

                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Food</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $grandTotal = 0; @endphp
                                            @foreach ($cartItems as $item)
                                                @php
                                                    $subtotal = $item->quantity * $item->unit_price;
                                                    $grandTotal += $subtotal;
                                                @endphp
                                                <tr id="row-{{ $item->id }}">
                                                    <td>{{ $item->food->name ?? 'Food Item' }}</td>
                                                    <td>৳{{ number_format($item->unit_price, 2) }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <button class="btn btn-sm btn-secondary qty-btn"
                                                                onclick="updateQty({{ $item->id }}, 'minus')">-</button>
                                                            <input type="text" id="qty-{{ $item->id }}"
                                                                value="{{ $item->quantity }}"
                                                                class="form-control qty-box mx-2" readonly>
                                                            <button class="btn btn-sm btn-secondary qty-btn"
                                                                onclick="updateQty({{ $item->id }}, 'plus')">+</button>
                                                        </div>
                                                    </td>
                                                    <td id="total-{{ $item->id }}">৳{{ number_format($subtotal, 2) }}
                                                    </td>
                                                    <td><button class="btn btn-danger btn-sm"
                                                            onclick="removeItem({{ $item->id }})">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="text-end mt-4">
                                        <h4>Grand Total: <span id="grandTotal">৳{{ number_format($grandTotal, 2) }}</span>
                                        </h4>
                                        <button class="btn btn-primary " id="checkoutBtn">Proceed to Checkout</button>
                                    </div>
                                </div>
                            </div>

                            <!-- ================= CHECKOUT SECTION ================= -->
                            <div id="checkoutSection" class="mt-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h4 class="mb-3">Checkout</h4>
                                        <form action="{{ route('order.place') }}" method="POST">
                                            @csrf

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $profile_info->name }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $profile_info->phone }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Team Name</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $profile_info->team->name }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Floor</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $profile_info->floor }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Row</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $profile_info->row }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Seat Number</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $profile_info->seat_number }}" disabled>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Payment Type</label>
                                                <select name="payment_type" class="form-control" required>
                                                    <option value="cod">Cash on Delivery</option>
                                                    <option value="due">Due</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Order Note (Optional)</label>
                                                <textarea name="note" class="form-control" rows="3" placeholder="Any special instructions..."></textarea>
                                            </div>

                                            <button type="submit" class="btn btn-success w-100">Place Order</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('script')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Show checkout section
        $('#checkoutBtn').click(function() {
            $('#checkoutSection').slideDown();
            $('html, body').animate({
                scrollTop: $('#checkoutSection').offset().top
            }, 600);
        });

        // Update quantity
        function updateQty(id, type) {
            $.ajax({
                url: "/cart/update/" + id,
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type
                },
                success: function(res) {
                    $("#qty-" + id).val(res.quantity);
                    $("#total-" + id).text("৳" + res.total);
                    $("#grandTotal").text("৳" + res.grand_total);
                }
            });
        }

        // Remove item
        function removeItem(id) {
            $.ajax({
                url: "/cart/remove/" + id,
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    $("#row-" + id).remove();
                    $("#grandTotal").text("৳" + res.grand_total);
                    if (res.empty) location.reload();
                }
            });
        }
    </script>

@endsection
