<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @can('dashboard.view')
                <li class="nav-item">
                    <a href="{{ route('icts.index') }}"
                        class="nav-link {{ request()->routeIs('icts.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-solid fa-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            @endcan
        </ul>
    </nav>
</div>
