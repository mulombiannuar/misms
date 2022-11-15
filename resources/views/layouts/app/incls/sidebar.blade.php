<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/dist/img/mulan-logo-new.png') }}" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light">&nbsp;</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/assets/dist/img/users/icon-male.png" class="img-circle elevation-2"
                    alt="{{ ucwords(Auth::user()->name) }}">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ ucwords(Auth::user()->name) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item has-treeview menu-open">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ $page_name == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">SYSTEM COMPONENTS</li>
                <li class="nav-item">
                    <a href="{{ route('profile') }}" class="nav-link {{ $page_name == 'profile' ? 'active' : '' }}">
                        <i class="fa fa-user nav-icon"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ $page_name == 'users' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users Management
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link">
                                    <i class="fa fa-arrow-right nav-icon"></i>
                                    <p>Manage Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-arrow-right nav-icon"></i>
                                    <p>User Roles</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                <li class="nav-item">
                    <a href="{{ route('admin.logs.index') }}"
                        class="nav-link {{ $page_name == 'logs' ? 'active' : '' }}">
                        <i class="fa fa-list nav-icon"></i>
                        <p>
                            System Logs
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.messages.index') }}"
                        class="nav-link {{ $page_name == 'messages' ? 'active' : '' }}">
                        <i class="fa fa-envelope nav-icon"></i>
                        <p>
                            Messages
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('account.password') }}"
                        class="nav-link {{ $page_name == 'password' ? 'active' : '' }}">
                        <i class="fa fa-unlock nav-icon"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ $page_name == 'settings' ? 'active' : '' }}">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            System Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.default-gradings.index') }}" class="nav-link">
                                <i class="fa fa-arrow-right nav-icon"></i>
                                <p>Default Gradings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sessions.index') }}" class="nav-link">
                                <i class="fa fa-arrow-right nav-icon"></i>
                                <p>Sessions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.school-details') }}" class="nav-link">
                                <i class="fa fa-arrow-right nav-icon"></i>
                                <p>School Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.periods.index') }}" class="nav-link">
                                <i class="fa fa-arrow-right nav-icon"></i>
                                <p>Lesson Periods</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-arrow-right nav-icon"></i>
                                <p>Notifications</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ 'logout' }}" class="nav-link"
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        @csrf
                        <i class="fa fa-power-off nav-icon"></i>
                        <p>
                            System Logout
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
