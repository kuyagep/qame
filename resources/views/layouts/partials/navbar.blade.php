<nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        <!-- App Name with Badge -->
        <li class="nav-item d-flex align-items-center">
            <a href="#" class="nav-link font-weight-bold text-dark px-2 text-md d-flex align-items-center">
                <span>{{ config('app.name', 'My Application') }}</span>
                <span class="badge badge-primary text-xs ml-2">PRO</span>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu Component -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell fa-fw"></i>
                @if ($unreadNotificationsCount > 0)
                    <span
                        class="badge badge-success navbar-badge font-weight-bold">{{ $unreadNotificationsCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 320px;">
                <span class="dropdown-item dropdown-header font-weight-bold text-secondary">
                    {{ $unreadNotificationsCount }} Unread Notifications
                </span>
                <div class="dropdown-divider"></div>

                @forelse($unreadNotifications as $notification)
                    <a href="{{ $notification->data['action_url'] ?? '#' }}"
                        class="dropdown-item d-flex align-items-start p-3 text-wrap notification-item"
                        data-id="{{ $notification->id }}">
                        <div class="mr-3 mt-1">
                            <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} fa-fw"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 text-sm font-weight-bold text-dark">{{ $notification->data['title'] }}</p>
                            <p class="mb-0 text-xs text-muted">{{ $notification->data['message'] }}</p>
                            <span class="text-xs text-secondary">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                @empty
                    <div class="dropdown-item text-center p-3 text-muted text-sm">
                        <i class="fas fa-check-circle text-success mb-2 d-block fa-lg"></i>
                        All caught up! Clear queue.
                    </div>
                    <div class="dropdown-divider"></div>
                @endforelse

                @if ($unreadNotificationsCount > 0)
                    <a href="#" id="mark-all-read"
                        class="dropdown-item dropdown-footer font-weight-bold text-info text-center py-2">
                        <i class="fas fa-check-double mr-1"></i> Mark All as Read
                    </a>
                @endif
            </div>
        </li>

        <!-- Fullscreen Button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>



        <!-- User Profile Dropdown Menu -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown"
                aria-expanded="false">
                <img src="{{ route('users.avatar', auth()->id()) }}" class="user-image img-circle elevation-1 mr-2"
                    alt="User Image" style="width: 32px; height: 32px; object-fit: cover;">
                <span class="d-none d-md-inline font-weight-bold">{{ Str::limit(auth()->user()->name, 20) }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User Header -->
                <div class="dropdown-header text-center bg-light p-3 border-bottom">
                    <img src="{{ route('users.avatar', auth()->id()) }}" class="img-circle elevation-1 mb-2"
                        alt="User Image" style="width: 70px; height: 70px; object-fit: cover;">
                    <p class="mb-0 font-weight-bold text-dark">{{ auth()->user()->name }}</p>
                    <small class="text-muted d-block">{{ auth()->user()->email ?? 'user@example.com' }}</small>

                    {{-- Role Badge --}}
                    @if (auth()->check() && auth()->user()->roles->isNotEmpty())
                        <div class="mt-1">
                            @foreach (auth()->user()->roles as $role)
                                <span class="badge badge-danger px-2 py-1 text-xs">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Profile Links -->
                <a href="{{ route('profile.index') }}" class="dropdown-item py-2">
                    <i class="fas fa-user-cog mr-2 text-primary"></i> Profile Settings
                </a>
                <div class="dropdown-divider"></div>

                <!-- Logout Link -->
                <a href="#" class="dropdown-item py-2 text-danger font-weight-bold" id="btnLogout">
                    <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                </a>
            </div>
        </li>

        <!-- Hidden Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </ul>
</nav>
