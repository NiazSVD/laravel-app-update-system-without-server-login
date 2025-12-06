<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    @php
                        $logo = App\Models\SiteSetting::first()->site_logo ?? 'uploads/site/default-logo.png';
                    @endphp

                    <a href="{{ route('admin.dashboard') }}"><img src="{{ asset($logo) }}" alt="Logo"
                            srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                {{-- <li class="sidebar-title">Menu</li> --}}

                {{-- Vendor menu --}}
                @role('vendor')
                    <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                            <i class="bi bi-ui-checks-grid"></i>
                            {{-- <span>Dashboard</span> --}}
                            <span>ড্যাশবোর্ড</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.food.vendorindexfood') ? 'active' : '' }}">
                        <a href="{{ route('admin.food.vendorindexfood') }}" class='sidebar-link'>
                            <i class="bi bi-card-checklist"></i>
                            {{-- <span>Food </span> --}}
                            <span>খাবার</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.food.vendorindex') ? 'active' : '' }}">
                        <a href="{{ route('admin.food.vendorindex') }}" class='sidebar-link'>
                            <i class="bi bi-egg-fried"></i>
                            {{-- <span>Food List</span> --}}
                            <span>খাবার তালিকা</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('vendor.order.list') ? 'active' : '' }}">
                        <a href="{{ route('vendor.order.list') }}" class='sidebar-link'>
                            <i class="bi bi-bag-check-fill"></i>
                            {{-- <span>Orders</span> --}}
                            <span>অর্ডার সমূহ</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('vendor.order.delivey.list') ? 'active' : '' }}">
                        <a href="{{ route('vendor.order.delivey.list') }}" class='sidebar-link'>
                            <i class="bi bi-truck"></i>
                            {{-- <span>Success</span> --}}
                            <span>সফল অর্ডার</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('vendor.order.cancel.list') ? 'active' : '' }}">
                        <a href="{{ route('vendor.order.cancel.list') }}" class='sidebar-link'>
                            <i class="bi bi-x-octagon-fill"></i>
                            {{-- <span>Cancel</span> --}}
                            <span>বাতিল অর্ডার</span>
                        </a>
                    </li>

                @endrole


                {{-- employee menus --}}
                @role('employee')
                    <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                            <i class="bi bi-ui-checks-grid"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('food.listAdmin') ? 'active' : '' }}">
                        <a href="{{ route('food.listAdmin') }}" class='sidebar-link'>
                            <i class="bi bi-card-checklist"></i>
                            <span>Food</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.user.order_list') ? 'active' : '' }}">
                        <a href="{{ route('admin.user.order_list') }}" class='sidebar-link'>
                            <i class="bi bi-card-checklist"></i>
                            <span>My Orders</span>
                        </a>
                    </li>
                @endrole


                {{-- delivery --}}
                @role('delivery')
                    <li class="sidebar-item {{ request()->routeIs('employee.order.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.order.list') }}" class='sidebar-link'>
                            <i class="bi bi-bag-check-fill"></i>
                            <span>My Orders</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('employee.delivery.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.delivery.list') }}" class='sidebar-link'>
                            <i class="bi bi-truck"></i>
                            <span>Delivery List</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('employee.cancel.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.cancel.list') }}" class='sidebar-link'>
                            <i class="bi bi-x-octagon-fill"></i>
                            <span>cancel List</span>
                        </a>
                    </li>
                @endrole


                {{-- Admin menus --}}
                @role('admin')

                    <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                            <i class="bi bi-ui-checks-grid"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.category.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.category.index') }}" class='sidebar-link'>
                            <i class="bi bi-collection-fill"></i>
                            <span>Category</span>
                        </a>
                    </li>

                    {{-- <li class="sidebar-item has-sub {{ request()->routeIs('admin.category.*') ? 'active open' : '' }}">

                        <a href="#" class="sidebar-link">
                            <i class="bi bi-collection-fill"></i>
                            <span>Category</span>
                        </a>

                        <ul class="submenu {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                            <li class="submenu-item {{ request()->routeIs('admin.category.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.category.index') }}">Category</a>
                            </li>
                        </ul>
                    </li> --}}
                    <li class="sidebar-item {{ request()->routeIs('food.listAdmin') ? 'active' : '' }}">
                        <a href="{{ route('food.listAdmin') }}" class='sidebar-link'>
                            <i class="bi bi-card-checklist"></i>
                            <span>Food </span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.food.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.food.index') }}" class='sidebar-link'>
                            <i class="bi bi-egg-fried"></i>
                            <span>Food List</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('employee.order.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.order.list') }}" class='sidebar-link'>
                            <i class="bi bi-bag-check-fill"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('employee.delivery.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.delivery.list') }}" class='sidebar-link'>
                            <i class="bi bi-truck"></i>
                            <span>Success</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('employee.cancel.list') ? 'active' : '' }}">
                        <a href="{{ route('employee.cancel.list') }}" class='sidebar-link'>
                            <i class="bi bi-x-octagon-fill"></i>
                            <span>cancel List</span>
                        </a>
                    </li>


                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-people-fill"></i>
                            <span>Admin Manage</span>
                        </a>

                        <ul class="submenu {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <li class="submenu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.index') }}">Admin Manage</a>
                            </li>

                            <li
                                class="submenu-item {{ request()->routeIs('admin.users.employee.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.employee.index') }}">Employee Manage</a>
                            </li>

                            <li class="submenu-item {{ request()->routeIs('admin.users.vendor.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.vendor.index') }}">Vendor Manage</a>
                            </li>

                            {{-- <li
                            class="submenu-item {{ request()->routeIs('admin.users.delivery.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.delivery.index') }}">Delivery Manage</a>
                        </li> --}}

                            <li class="submenu-item {{ request()->routeIs('admin.users.other.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.other.index') }}">Other</a>
                            </li>
                        </ul>
                    </li>

                    @canany(['role view', 'role create', 'role edit', 'role delete'])
                        <li class="sidebar-item {{ request()->routeIs('admin.roles') ? 'active' : '' }}">
                            <a href="{{ route('admin.roles') }}" class='sidebar-link'>
                                <i class="bi bi-diagram-3-fill"></i>
                                <span>Role Manage</span>
                            </a>
                        </li>
                    @endcanany

                    <li class="sidebar-item {{ request()->routeIs('admin.team.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.team.index') }}" class='sidebar-link'>
                            <i class="bi bi-person-lines-fill"></i>
                            <span>Teams</span>
                        </a>
                    </li>

                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.settings.*') ? 'active open' : '' }}">

                        <a href="#" class="sidebar-link">
                            <i class="bi bi-gear-fill"></i>
                            <span>Settings</span>
                        </a>

                        <ul class="submenu {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <li class="submenu-item {{ request()->routeIs('admin.settings.site') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.site') }}">General Setting</a>
                            </li>
                        </ul>
                    </li>
                @endrole

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
