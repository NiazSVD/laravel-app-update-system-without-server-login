<header class="mb-3">
    <nav class="navbar navbar-expand navbar-light bg-light shadow-sm ">
        <div class="container-fluid">
            <!-- Burger Button -->
            <a href="#" class="burger-btn d-block me-3">
                <i class="bi bi-list fs-3"></i>
            </a>

            <!-- Navbar Toggler for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Left Side (can add links here if needed) -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Optional menu items -->
                </ul>

                <!-- Right Side -->
                <div class="d-flex align-items-center ms-auto">

                    <!-- Notification Icon -->
                    {{-- <a href="#" class="position-relative me-4 text-dark">
                        <i class="bi bi-bell fs-4"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.65rem; min-width:16px; height:16px; display:flex; align-items:center; justify-content:center;">

                        </span>
                    </a> --}}
                    {{-- <a href="#" class="position-relative me-4 text-dark">
                        <i class="bi bi-bell fs-4"></i>

                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.65rem; min-width:16px; height:16px; display:flex; align-items:center; justify-content:center;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </a> --}}
                    <a href="{{ route('employee.order.list') }}" class="position-relative me-4 text-dark">
                        <i class="bi bi-bell fs-4"></i>

                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.65rem; min-width:16px; height:16px; display:flex; align-items:center; justify-content:center;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </a>




                    <!-- Cart Icon -->
                    @role('employee')
                    <a href="{{ route('cart.index') }}" class="position-relative text-dark me-4">
                        <i class="bi bi-cart3 fs-4"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.65rem; min-width:16px; height:16px; display:flex; align-items:center; justify-content:center;"
                            id="cartCount">
                            {{ \App\Models\Cart::where('employee_id', auth()->id())->sum('quantity') }}
                        </span>
                    </a>
                    @endrole

                    @role('admin')
                    <a href="{{ route('cart.index') }}" class="position-relative text-dark me-4">
                        <i class="bi bi-cart3 fs-4"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.65rem; min-width:16px; height:16px; display:flex; align-items:center; justify-content:center;"
                            id="cartCount">
                            {{ \App\Models\Cart::where('employee_id', auth()->id())->sum('quantity') }}
                        </span>
                    </a>
                    @endrole

                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex align-items-center">
                                <div class="user-name text-end me-3">
                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                    <p class="mb-0 text-sm text-gray-600">
                                        {{ Str::title(auth()->user()->roles->first()->name) }}
                                    </p>
                                </div>
                                <div class="user-img">
                                    <div class="avatar avatar-md">
                                        <img src="{{ asset('backend/assets/images/faces/1.jpg') }}"
                                            class="rounded-circle">
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li>
                                {{-- <h6 class="dropdown-header">Role:{{
                                    Str::title(auth()->user()->roles->first()->name) }}
                                </h6> --}}
                            </li>

                            {{-- for employee menu --}}
                            @role('employee')
                                <li><a class="dropdown-item" href="{{ route('cart.index') }}">
                                        <i class="icon-mid bi bi-cart me-2"></i>Cart</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.user.order_list') }}">
                                        <i class="icon-mid bi bi-list-stars me-2"></i>My Orders
                                    </a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.profile.edit', auth()->user()->id) }}"><i
                                            class="icon-mid bi bi-person me-2"></i>My Profile
                                    </a>
                                </li>
                            @endrole

                            {{-- for vendor menu --}}
                            @role('vendor')
                                <li><a class="dropdown-item" href="{{route('vendor.order.list')}}"><i
                                    class="icon-mid bi bi-list-stars me-2"></i>অর্ডার সমূহ</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.profile.edit', auth()->user()->id) }}"><i
                                            class="icon-mid bi bi-person me-2"></i>প্রোফাইল
                                    </a>
                                </li>
                            @endrole

                            {{-- for admin menu --}}
                            @role('admin')
                                <li><a class="dropdown-item" href="{{ route('cart.index') }}">
                                    <i class="icon-mid bi bi-cart me-2"></i>Cart</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('employee.order.list') }}"><i
                                            class="icon-mid bi bi-list-stars me-2"></i>Orders
                                    </a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.profile.edit', auth()->user()->id) }}"><i
                                            class="icon-mid bi bi-person me-2"></i>My Profile
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                        Settings
                                    </a>
                                </li>
                            @endrole
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </nav>
</header>
