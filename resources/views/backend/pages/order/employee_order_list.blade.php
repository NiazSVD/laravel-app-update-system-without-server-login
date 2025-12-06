@extends('backend.master')

@section('title', 'Employee Order List')

@section('content')

<div class="page-content d-flex justify-content-center align-items-center text-dark mb-5">
    <section class="row g-4 w-100 d-flex justify-content-center">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h3 class="card-title">My Order List</h3>
            </div>
            <div class="m-3">
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-center mt-3">
                        <form method="GET" action="{{ route('employee.order.list') }}">
                            <div class="d-flex gap-2">

                                <!-- Employee ID Input -->
                                <input type="text" name="employee_code" class="form-control"
                                    placeholder="Enter Employee Code" value="{{ request('employee_code') }}">

                                <!-- From Date -->
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ request('from_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}"
                                    placeholder="From">

                                <!-- To Date -->
                                <input type="date" name="to_date" class="form-control"
                                    value="{{ request('to_date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                    placeholder="To">


                                <button type="submit" class="btn btn-success">Filter</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-12 d-flex justify-content-center mt-3">
                        <div class="d-flex flex-wrap">

                            <!-- Total Orders -->
                            <div class="card bg-primary text-center m-2" style="width: 150px; height: 85px;">
                                <div class="card-body ">
                                    <h6 class="card-title text-white" style="font-size: 15px">Total Orders</h6>
                                    <p class="card-text text-white">{{ $totalOrders }}</p>
                                </div>
                            </div>

                            <!-- Paid Orders -->
                            <div class="card bg-success text-center m-2" style="width: 150px; height: 85px;">
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 15px">Paid Orders</h5>
                                    <p class="card-text text-white">{{ $paid }}</p>
                                </div>
                            </div>

                            <!-- Unpaid Orders -->
                            <div class="card bg-danger text-center m-2" style="width: 150px; height: 85px;">
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 15px">Unpaid Orders</h5>
                                    <p class="card-text text-white">{{ $unpaid }}</p>
                                </div>
                            </div>

                            <!-- Due Amount -->
                            <div class="card bg-warning text-center m-2" style="width: 150px; height: 85px;">
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 15px">Due Amount</h5>
                                    <p class="card-text text-white">{{ number_format($dueAmount, 2) }} ৳</p>
                                </div>
                            </div>
                            <!-- Paid Amount -->
                            <!-- Paid Amount -->
                            <div class="card bg-success text-center m-2" style="width: 150px; height: 85px;">
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 15px">Paid Amount</h5>
                                    <p class="card-text text-white">{{ number_format($paidAmount, 2) }} ৳</p>
                                </div>
                            </div>


                            <!-- Total Amount -->
                            <div class="card bg-info text-center m-2" style="width: 150px; height: 85px;">
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 15px">Total Amount</h5>
                                    <p class="card-text text-white">{{ number_format($totalAmount, 2) }} ৳</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="card-content pt-4 mb-3 pb-3">
                <div class="table-responsive  ">
                    <table class="table mb-0 table-lg" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Order Number</th>
                                <th>Payment Type</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="mb-5">
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->employee?->employee_code ?? ('N/A' ?? 'N/A') }}</td>
                                {{-- <td>{{ $order->vendor?->name ?? 'N/A' }}</td> --}}
                                <td>{{ $order->employee?->name ?? ('N/A' ?? 'N/A') }}</td>
                                <td>{{ $order->order_number }}</td>


                                {{-- <td>{{ ucfirst($order->payment_type) }}</td> --}}
                                <td>
                                    @php
                                    $type = strtolower($order->payment_type);

                                    if ($type == 'cod') {
                                    $class = 'badge bg-success'; // Green
                                    } elseif ($type == 'due') {
                                    $class = 'badge bg-warning text-dark'; // Yellow/Orange
                                    } else {
                                    $class = 'badge bg-secondary'; // Others
                                    }
                                    @endphp

                                    <span class="{{ $class }}">{{ strtoupper($type) }}</span>
                                </td>

                                <td>
                                    @php
                                    $payment = $order->payment_status;
                                    $class = $payment == 'paid' ? 'badge bg-success' : 'badge bg-danger';
                                    @endphp

                                    <span class="{{ $class }}">{{ ucfirst($payment) }}</span>
                                </td>

                                {{-- <td>{{ ucfirst($order->payment_status) }}</td> --}}
                                {{-- <td>{{ ucfirst($order->order_status) }}</td> --}}
                                <td>
                                    @php
                                    $status = $order->order_status;
                                    $class = '';

                                    if ($status == 'pending') {
                                    $class = 'badge bg-warning text-dark';
                                    } elseif ($status == 'delivered') {
                                    $class = 'badge bg-success';
                                    } elseif ($status == 'cancel') {
                                    $class = 'badge bg-danger';
                                    } else {
                                    $class = 'badge bg-danger';
                                    }
                                    @endphp

                                    <span class="{{ $class }}">{{ ucfirst($status) }}</span>
                                </td>


                                <td>{{ $order->created_at->timezone('Asia/Dhaka')->format('d-m-Y h:i A') }}</td>

                                <td>{{ number_format($order->total_amount, 2) }} Tk</td>
                                <td style="width: 10%;">
                                    <div class="d-flex">

                                        <button type="button" class="btn btn-sm btn-danger m-1 view-details"
                                            data-order="{{ $order->toJson() }}">
                                            View
                                        </button>

                                                <button
                                                    class="btn btn-sm btn-primary m-1 edit-status"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateStatusModal"
                                                    data-id="{{ $order->id }}"
                                                    data-status="{{ $order->order_status }}">
                                                    Action
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No order found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>
    {{-- SINGLE MODAL --}}
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="modal-body-content">
                    Loading...
                </div>
            </div>
        </div>
    </div>
    {{-- Status Change --}}

    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST" id="updateStatusForm">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title text-white">Update Order Status</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label class="form-label fw-semibold mb-2">Select Status</label>

                        <select class="form-select shadow-sm" name="order_status" id="modal_order_status" required>
                            <option value="pending">Pending</option>
                            <option value="delivered">Delivered</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="cod">COD</option>
                            <option value="due">Due</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                    </div>

                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" form="updateStatusForm" class="btn btn-success px-4">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <style>
        #updateStatusModal .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 35px rgba(0,0,0,0.15);
        }

        #updateStatusModal .modal-header {
            background: linear-gradient(135deg, #3889da, #002864);
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 18px 22px;
        }

        #updateStatusModal .modal-title {
            font-weight: 600;
            font-size: 18px;
        }

        #updateStatusModal .btn-close {
            filter: invert(1);
        }

        #updateStatusModal .modal-body {
            padding: 25px;
        }

        #updateStatusModal select.form-select {
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 15px;
        }

        #updateStatusModal .modal-footer {
            padding: 15px 22px;
            border-top: 1px solid #eee;
        }
    </style>
@endsection


@section('script')

<script>
    $(document).on('click', '.view-details', function () {

            let order = $(this).data('order');

            // Build Items Table
            let itemsHtml = '';
            order.items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td>${item.food?.name ?? 'N/A'}</td>
                        <td>${item.food?.category?.name ?? 'N/A'}</td>
                        <td>${item.quantity}</td>
                        <td>${Number(item.unit_price).toFixed(2)}</td>
                        <td>${(item.quantity * item.unit_price).toFixed(2)}</td>
                    </tr>
                `;
            });

            // Left Side: Order Info
            let leftHtml = `
                <div style="flex:1;">
                    <h5>#${order.order_number}</h5>
                    <p><strong>Date:</strong> ${formatDateBD(order.created_at)}</p>
                    <p><strong>Vendor:</strong> ${order.vendor?.name ?? 'N/A'}</p>
                    <p><strong>Assistant:</strong> ${order.assistant?.name ?? 'N/A'}</p>
                    <p><strong>Total Amount:</strong> ${Number(order.total_amount).toFixed(2)} Tk</p>
                    <p>
                        <strong>Order Status:</strong>
                        <span class="badge ${getStatusClass(order.order_status)}">
                            ${order.order_status ? capitalize(order.order_status) : 'N/A'}
                        </span>
                    </p>
                </div>
            `;

            // Right Side: User Info
            let rightHtml = `
                <div style="flex:1; border-left:1px solid #ddd; padding-left:15px;">
                    <h5>Employee Info</h5>
                    <p><strong>Employee ID:</strong> ${order.employee?.employee_code ?? 'N/A'}</p>
                    <p><strong>Team:</strong> ${order.employee?.team?.name ?? 'N/A'}</p>
                    <p><strong>Name:</strong> ${order.employee?.name ?? 'N/A'}</p>
                    <p><strong>Email:</strong> ${order.employee?.email ?? 'N/A'}</p>
                    <p><strong>Phone:</strong> ${order.employee?.phone ?? 'N/A'}</p>
                </div>
            `;

            // Top Section: Order Info + User Info
            let topSection = `
                <div style="display:flex; gap:20px; margin-bottom:20px;">
                    ${leftHtml}
                    ${rightHtml}
                </div>
            `;

            // Items Table Section (full width)
            let itemsSection = `
                <h6><strong>Items</strong></h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Food Name</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>
            `;

            // Combine Top + Items
            let html = `
                ${topSection}
                ${itemsSection}
            `;

            $('#modal-body-content').html(html);
            $('#orderModal').modal('show');
        });




        function getStatusClass(status){
            switch (status) {
                case 'pending':
                    return 'bg-warning text-dark';
                case 'delivered':
                    return 'bg-success';
                case 'cancelled':
                    return 'bg-danger';
                default:
                    return 'bg-secondary';
            }
        }

        function capitalize(text){
            return text.charAt(0).toUpperCase() + text.slice(1);
        }



        function formatDateBD(dateString) {
            const date = new Date(dateString);

            return date.toLocaleString('en-GB', {
                timeZone: 'Asia/Dhaka',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }




        

        $(document).on("click", ".edit-status", function () {
            let id = $(this).data("id");
            let status = $(this).data("status");

            $("#modal_order_status").val(status);

            $("#updateStatusForm").attr("action", "/order/status/" + id);
        });
    </script>
@endsection
