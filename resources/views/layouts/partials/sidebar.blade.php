<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            {{-- Dashboard --}}
            @can('dashboard.view')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-solid fa-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            @endcan
            @role('Staff')
                <li class="nav-item">
                    <a href="{{ route('participant.dashboard') }}"
                        class="nav-link {{ request()->routeIs('participant.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-solid fa-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            @endrole
            @role('Admin')
                <li class="nav-item">
                    <a href="{{ route('trainings.index') }}"
                        class="nav-link {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>L&D</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('facilitators.index') }}"
                        class="nav-link {{ request()->routeIs('facilitators.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Facilitators</p>
                    </a>
                </li>
            @endrole


            {{-- Administration --}}
            @if (auth()->user()->canAny(['users.view', 'roles.view', 'permissions.view']))
                <li class="nav-item {{ request()->routeIs('users.*', 'roles.*', 'permissions.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('users.*', 'roles.*', 'permissions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Administration
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-fw fa-user-shield"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}"
                                class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-fw fa-key"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        @can('departments.view')
                            <li class="nav-item">
                                <a href="{{ route('departments.index') }}"
                                    class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>Departments</p>
                                </a>
                            </li>
                        @endcan
                        @can('offices.view')
                            <li class="nav-item">
                                <a href="{{ route('offices.index') }}"
                                    class="nav-link {{ request()->routeIs('offices.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>Offices</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            {{-- Reports --}}

            @can('events.view')
                <li class="nav-item">
                    <a href="{{ route('events.index') }}"
                        class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Events</p>
                    </a>
                </li>
            @endcan

            @can('settings.manage')
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}"
                        class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
            @endcan

            @can('announcements.manage')
                <li class="nav-item">
                    <a href="{{ route('announcements.index') }}"
                        class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn text-warning"></i>
                        <p>
                            Announcements
                            @php
                                $activeAnnouncementsCount = \App\Models\Announcement::where('is_active', true)->count();
                            @endphp
                            @if ($activeAnnouncementsCount > 0)
                                <span class="badge bg-warning text-dark float-end">{{ $activeAnnouncementsCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>
            @endcan

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
