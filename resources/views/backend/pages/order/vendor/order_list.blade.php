@extends('backend.master')

@section('title', 'Employee Order List')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h3 class="card-title">My Order List</h3>
        </div>
        <div class="m-3">
            <div class="row">
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('employee.order.list') }}">
                        <div class="d-flex">

                            <select name="filter" class="form-select">
                                <option value="">All</option>
                                <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>This Week
                                </option>
                                <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>This Month
                                </option>
                                <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>This Year
                                </option>
                            </select>
                            <button type="submit" class="btn btn-success rounded ms-2">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-content pt-4 mb-3 pb-3">


            <div class="table-responsive  ">
                {{-- <select id="statusFilter" class="form-select w-auto">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Canceled</option>
                </select> --}}


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
                                <td>{{ $loop->iteration }}</td>
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

                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-primary dropdown-toggle m-1" type="button"
                                                id="actionDropdown{{ $order->id }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Action
                                            </button>


                                            <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $order->id }}"
                                                style="padding: 5px 10px; min-width: 160px;">
                                                <li style="border-bottom: 1px solid #ddd">
                                                    <form action="{{ route('order.pending', $order->id) }}" method="get">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">Pending</button>
                                                    </form>
                                                </li>
                                                <li style="border-bottom: 1px solid #ddd">
                                                    <form action="{{ route('order.delivered', $order->id) }}"
                                                        method="get" style="margin-bottom:5px;">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">Delivered</button>
                                                    </form>
                                                </li>
                                                <li style="border-bottom: 1px solid #ddd">
                                                    <form action="{{ route('order.paid', $order->id) }}" method="get"
                                                        style="margin-bottom:5px;">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">Paid</button>
                                                    </form>
                                                </li>

                                                <li style="border-bottom: 1px solid #ddd">
                                                    <form action="{{ route('order.unpaid', $order->id) }}" method="get"
                                                        style="margin-bottom:5px;">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">Unpaid</button>
                                                    </form>
                                                </li>
                                                <li style="border-bottom: 1px solid #ddd">
                                                    <form action="{{ route('order.cod', $order->id) }}" method="get">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">Cod</button>
                                                    </form>
                                                </li>

                                                <li style="border-bottom: 1px solid #ddd">
                                                    <form action="{{ route('order.due', $order->id) }}" method="get">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">Due</button>
                                                    </form>
                                                </li>

                                                <li style="border-bottom: 1px solid #ddd">
                                                    <form action="{{ route('order.cancel', $order->id) }}" method="get">
                                                        @csrf
                                                        <button class="dropdown-item text-danger"
                                                            type="submit">Cancel</button>
                                                    </form>
                                                </li>



                                            </ul>
                                        </div>
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


@endsection


@section('script')

    <script>


        // $(document).ready(function () {
        //     $('#statusFilter').on('change', function () {
        //         let filter = $(this).val().toLowerCase();

        //         $('#data-table tbody tr').each(function () {
        //             let rowStatus = $(this).find('td:nth-child(6)').text().trim().toLowerCase();

        //             if (filter === '' || rowStatus.includes(filter)) {
        //                 $(this).show();
        //             } else {
        //                 $(this).hide();
        //             }
        //         });
        //     });
        // });








        ///order details modal
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
    </script>
@endsection
