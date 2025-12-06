@extends('backend.master')
@section('title', 'Softvence Food Dashboard')
@section('admin', 'active')

@section('content')
    <div class="page-content">
        <section class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <h4>Hi, {{ auth()->user()->name }}</h4>
                                        <h6 id="greeting" class="text-muted fw-500 pt-2">Good morning</h6>
                                        <p class="pb-2">Here’s what’s happening today. Check system insights and recent
                                            updates.
                                        </p>
                                    </div>
                                    <div>
                                        @role('admin')
                                            <a href="{{ route('admin.settings.site') }}" class="btn btn-primary">
                                                Go to Settings
                                            </a>
                                        @endrole

                                        @role('vendor')
                                            <a href="{{ route('vendor.order.list') }}" class="btn btn-primary">
                                                Order List
                                            </a>
                                        @endrole
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-5">
                                <div class="d-flex align-items-center justify-content-end mt-5 mt-lg-3">
                                    <img class="w-75" src="{{ asset('backend/assets/img/avatars/dashobard.png') }}"
                                        alt="Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                @role('admin')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <i class="bi bi-people"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total User</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['total_user'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <i class="bi bi-shop"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total Vendor</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['total_vendor'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total Employee</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['total_employee'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon red">
                                                <i class="bi bi-egg-fried"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total Food Post</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['total_food_post'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole


                @role('vendor')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <i class="bi bi-egg-fried"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">My Food Post</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['my_food_post'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <i class="bi bi-people"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Pending Order</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['pending_order'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                <i class="bi bi-shop"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Success Order</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['delivered_order'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon  red">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Cancelled Order</h6>
                                            <h6 class="font-extrabold mb-0">{{ $data['cancelled_order'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole
            </div>
        </section>
    </div>

    <script>
        (function() {
            // timezone to use
            const timeZone = 'Asia/Dhaka';

            // get hour in specified timezone using Intl API
            const fmt = new Intl.DateTimeFormat('en-US', {
                hour: 'numeric',
                hour12: false,
                timeZone
            });
            const parts = fmt.formatToParts(new Date());
            const hourPart = parts.find(p => p.type === 'hour');
            const hour = hourPart ? parseInt(hourPart.value, 10) : new Date().getHours();

            // choose greeting
            let greeting = 'Hello';
            if (hour >= 5 && hour < 12) greeting = 'Good morning';
            else if (hour >= 12 && hour < 17) greeting = 'Good afternoon';
            else if (hour >= 17 && hour < 21) greeting = 'Good evening';
            else greeting = 'Good night';

            // optional: customize with user name
            const userName = ''; // put name here if you have it, e.g. "Habibur"
            const finalText = userName ? `${greeting}, ${userName}` : greeting;

            // update DOM
            const el = document.getElementById('greeting');
            if (el) el.textContent = finalText;
        })();
    </script>
@endsection
