<body class="hold-transition sidebar-mini sidebar-collapse text-sm">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user mr-2"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('/profile') }}">
                            <i class="fa fa-user-circle mr-2"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                this.closest('form').submit();"
                                class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                        </form>
                    </div>
                </li>
            </ul>

        </nav>
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            {{-- <aside class="main-sidebar sidebar-dark-primary elevation-4"> --}}
            <aside class="main-sidebar custom-sidebar elevation-4">
                <div class="sidebar">
                    <div class="user-panel d-flex"
                        style="background-color: white;height: 3rem; width: 20.2rem; margin-left: -0.5rem; ">
                        <!-- For Small Logo -->
                        <img src="{{ asset('images/logo.png') }}" alt="Cogent Logo"
                            class="brand-image-xs logo-xs mt-0.1"
                            style="height: 2rem;width: 2.6rem;margin-left: -0.4rem;">
                        <!-- For Big Logo -->
                        <img src="{{ asset('images/CogentLogo.svg') }}" alt="Cogent Logo" class="brand-image-xl logo-xl"
                            style="height: 2rem;width: 9.5rem;margin-left: 2rem">
                    </div>
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}"
                                    class="nav-link {{ Request::segment(1) == 'dashboard' || Request::segment(1) == '' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-home"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('profile') }}"
                                    class="nav-link {{ Request::segment(1) == 'profile' || Request::segment(1) == '' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>User Profile</p>
                                </a>
                            </li>
                            @if (Auth::user()->role === 'Admin')
                                <li class="nav-item">
                                    <a href="{{ route('userslist') }}"
                                        class="nav-link {{ Request::segment(1) == 'userslist' || Request::segment(1) == '' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Users List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('userdetails') }}"
                                        class="nav-link {{ Request::segment(1) == 'userdetails' || Request::segment(1) == '' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-table"></i>
                                        <p>Enrollment</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->routeIs('manageCourse.list', 'manageModule.list', 'manageContent.list') ? 'menu-open' : '' }}">
                                    <a href="#"
                                        class="nav-link {{ request()->routeIs('manageCourse.list', 'manageModule.list', 'manageContent.list') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-graduation-cap"></i>
                                        <p>
                                            Manage Courses
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('manageCourse.list') }}"
                                                class="nav-link {{ request()->routeIs('manageCourse.list') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Courses</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('manageModule.list') }}"
                                                class="nav-link {{ request()->routeIs('manageModule.list') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Modules</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('manageContent.list') }}"
                                                class="nav-link {{ request()->routeIs('manageContent.list') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Content</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->role === 'Admin' || Auth::user()->role === 'L3')
                                <li
                                    class="nav-item {{ in_array(request()->path(), ['module_completetion', 'level_upgrade_approval']) ? 'menu-open' : '' }}">
                                    <a href="#"
                                        class="nav-link {{ in_array(request()->path(), ['module_completetion', 'level_upgrade_approval']) ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Session Approver Tab<i class="right fas fa-angle-left"></i></p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('module_completetion') }}"
                                                class="nav-link {{ request()->routeIs('module_completetion') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Module Completetion</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('level_upgrade_approval') }}"
                                                class="nav-link {{ request()->routeIs('level_upgrade_approval') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Level Upgrade</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->role !== 'Admin')
                                <li class="nav-item">
                                    <a href="{{ route('coursemodules') }}"
                                        class="nav-link {{ Request::segment(1) == 'coursemodules' || Request::segment(1) == '' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-book"></i>
                                        <p>Course Modules</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('certificate') }}"
                                        class="nav-link {{ Request::segment(1) == 'certificate' || Request::segment(1) == '' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-award"></i>
                                        <p>Certificate of Completion</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        this.closest('form').submit(); "
                                        class="nav-link">
                                        <i class="nav-icon fas fa-sign-out-alt"></i>
                                        <p>Logout</p>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
        </form>
