<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DTS Panel') - Schools Division Office</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand-primary: #0046E4;
            --brand-primary-hover: #0032A0;
            --brand-primary-light: rgba(0, 70, 228, 0.06);
            --brand-dark: #0A0F1D;
            --card-border: #E6ECF8;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --primary: var(--brand-primary);
            --primary-hover: var(--brand-primary-hover);
            --primary-light: var(--brand-primary-light);
            --sidebar-bg: var(--brand-dark);
            --sidebar-hover: #1E293B;
            --body-bg: #F8FAFC;
            --border: var(--card-border);
            --text: var(--text-main);
            --shadow-sm: 0 .125rem .25rem rgba(0, 0, 0, .04);
            --radius: 10px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--body-bg) !important;
            color: var(--text);
            font-family: system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .app-container {
            display: flex;
            position: relative;
            min-height: 100vh;
        }

        /* SIDEBAR WITH DROP CONTROLS */
        .main-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.3s ease-in-out;
        }

        .brand-link {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .brand-link i {
            color: var(--primary);
            font-size: 1.4rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, .75);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link:hover,
        .dropdown-trigger:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .nav-link.active {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
        }

        .nav-item-dropdown {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .dropdown-checkbox {
            display: none;
        }

        .dropdown-trigger {
            cursor: pointer;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center;
        }

        .dropdown-trigger .trigger-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-trigger .arrow-icon {
            font-size: 0.8rem;
            transition: transform 0.2s ease-in-out;
        }

        .sub-sidebar-menu {
            list-style: none;
            padding-left: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease-out, margin 0.25s ease;
            margin-top: 0;
        }

        .dropdown-checkbox:checked~.sub-sidebar-menu {
            max-height: 200px;
            margin-top: 0.35rem;
            margin-bottom: 0.35rem;
        }

        .dropdown-checkbox:checked~.dropdown-trigger .arrow-icon {
            transform: rotate(180deg);
        }

        .sub-nav-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 1rem;
            color: rgba(255, 255, 255, .6);
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .sub-nav-link:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .sub-nav-link.active {
            background: var(--brand-primary-light);
            color: #fff;
            font-weight: 600;
            border-left: 3px solid var(--primary);
            border-radius: 0 6px 6px 0;
        }

        .content-container {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            width: 100%;
            transition: margin-left 0.3s ease-in-out;
        }

        .main-navbar {
            height: 65px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: var(--shadow-sm);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle-btn {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-main);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
        }

        .navbar-timestamp {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .content-wrapper {
            padding: 2rem;
            flex-grow: 1;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .content-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--brand-dark);
        }

        /* DATA CONTROLLERS */
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: var(--radius);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-navbar {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-logout {
            background: rgba(220, 53, 69, 0.06);
            color: #DC3545;
            border: 1px solid rgba(220, 53, 69, 0.15);
        }

        .btn-logout:hover {
            background: #DC3545;
            color: #fff;
        }

        .data-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .data-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .data-card-header h4 {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .dts-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.925rem;
            min-width: 700px;
        }

        .dts-table th {
            background: #F8FAFC;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .dts-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.775rem;
            font-weight: 600;
        }

        .bg-light-blue {
            background: rgba(0, 70, 228, 0.08);
            color: var(--primary);
        }

        .bg-light-green {
            background: rgba(25, 135, 84, 0.08);
            color: #198754;
        }

        .bg-light-orange {
            background: rgba(255, 193, 7, 0.12);
            color: #B27B00;
        }

        .bg-light-red {
            background: rgba(220, 53, 69, 0.08);
            color: #DC3545;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.25rem;
            padding: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-control {
            padding: 0.65rem 0.75rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            font-family: inherit;
            font-size: 0.9rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--brand-primary-light);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 15, 29, 0.4);
            backdrop-filter: blur(2px);
            z-index: 1030;
        }

        @media (max-width: 991.98px) {
            .main-sidebar {
                transform: translateX(-100%);
            }

            .content-container {
                margin-left: 0 !important;
            }

            body.sidebar-open .main-sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }

            .navbar-timestamp {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="app-container">
        <!-- Unified Sidebar Navigation -->
        <aside class="main-sidebar" id="mainSidebar">
            <a href="{{ route('dts.dashboard') }}" class="brand-link">
                <i class="fa-solid fa-folder-tree"></i><span>SDO-DTS Panel</span>
            </a>
            <ul class="sidebar-menu">
                <li><a href="{{ route('dts.dashboard') }}"
                        class="nav-link {{ request()->routeIs('dts.dashboard') ? 'active' : '' }}"><i
                            class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="nav-item-dropdown">
                    <input type="checkbox" id="dts-routing-menu" class="dropdown-checkbox"
                        {{ request()->is('documents/*') ? 'checked' : '' }}>
                    <label for="dts-routing-menu" class="nav-link dropdown-trigger">
                        <span class="trigger-content"><i class="fa-solid fa-route"></i> Document Routing</span>
                        <i class="fa-solid fa-chevron-down arrow-icon"></i>
                    </label>
                    <ul class="sub-sidebar-menu">
                        <li><a href="{{ route('documents.received') }}"
                                class="sub-nav-link {{ request()->routeIs('documents.received') ? 'active' : '' }}"><i
                                    class="fa-solid fa-arrow-down-left"></i> Received</a></li>
                        <li><a href="{{ route('documents.released') }}"
                                class="sub-nav-link {{ request()->routeIs('documents.released') ? 'active' : '' }}"><i
                                    class="fa-solid fa-arrow-up-right"></i> Released</a></li>
                        <li><a href="{{ route('documents.deferred') }}"
                                class="sub-nav-link {{ request()->routeIs('documents.deferred') ? 'active' : '' }}"><i
                                    class="fa-solid fa-circle-pause"></i> Deferred</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('dts.documents.index') }}"
                        class="nav-link {{ request()->routeIs('dts.documents.index') ? 'active' : '' }}"><i
                            class="fa-solid fa-file-invoice"></i> All Documents</a></li>
                <li><a href="{{ route('dts.offices.index') }}"
                        class="nav-link {{ request()->routeIs('dts.offices.index') ? 'active' : '' }}"><i
                            class="fa-solid fa-building-shield"></i> Manage Offices</a></li>
                <li><a href="{{ route('dts.users.index') }}"
                        class="nav-link {{ request()->routeIs('dts.users.index') ? 'active' : '' }}"><i
                            class="fa-solid fa-users-gear"></i> User Accounts</a></li>
                <li><a href="{{ route('dts.logs.index') }}"
                        class="nav-link {{ request()->routeIs('dts.logs.index') ? 'active' : '' }}"><i
                            class="fa-solid fa-clock-rotate-left"></i> System Logs</a></li>
            </ul>
        </aside>

        <div class="content-container">
            <nav class="main-navbar">
                <div class="navbar-left">
                    <button class="sidebar-toggle-btn" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                    <div class="navbar-timestamp"><i class="fa-regular fa-clock"></i><span
                            id="currentSystemTime"></span></div>
                </div>
                <div class="navbar-actions">
                    <a href="/" class="btn btn-navbar"><i class="fa-solid fa-house"></i><span>Home</span></a>
                    <a href="#" class="btn btn-logout" onclick="alert('Logout trigger');"><i
                            class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
                </div>
            </nav>
            <main class="content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            if (toggle) toggle.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
            if (overlay) overlay.addEventListener('click', () => document.body.classList.remove('sidebar-open'));

            function updateClock() {
                const el = document.getElementById('currentSystemTime');
                if (el) el.textContent = new Date().toLocaleDateString('en-US', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
</body>

</html>
