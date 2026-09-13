<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Division Management Information System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            /* Brand Palette */
            --brand-primary: #861408;
            /* Dark Red (Dominant) */
            --brand-primary-hover: #650f06;
            /* Darker Red for Hover states */
            --brand-accent: #E17E10;
            /* Warm Amber/Gold (Accent) */
            --brand-accent-hover: #c46a0a;
            /* Darker Accent for Hover states */
            --brand-primary-light: rgba(134, 20, 8, 0.08);
            --brand-accent-light: rgba(225, 126, 16, 0.12);

            /* Typography & Neutral Highlights */
            --text-brand: #E17E10;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --bg-canvas: #F8FAFC;
            --card-border: #E2E8F0;
            --card-hover-border: #E17E10;

            /* Gradients */
            --brand-gradient: linear-gradient(135deg, #861408 0%, #500b04 100%);
            --brand-accent-gradient: linear-gradient(135deg, #E17E10 0%, #B86008 100%);
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--text-main);
            background-color: var(--bg-canvas) !important;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Modern Glass Navbar */
        .navbar {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background-color: rgba(255, 255, 255, 0.92) !important;
            border-bottom: 2px solid var(--brand-accent) !important;
            transition: all 0.3s ease;
        }

        .navbar-brand img {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        /* Buttons & Interactive Elements */
        .btn {
            padding: 0.55rem 1.25rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Primary Action Button (Dark Red) */
        .btn-primary {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(134, 20, 8, 0.15);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--brand-primary-hover) !important;
            border-color: var(--brand-primary-hover) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(134, 20, 8, 0.3) !important;
        }

        /* Accent Button (Amber/Gold) */
        .btn-accent {
            background-color: var(--brand-accent) !important;
            border-color: var(--brand-accent) !important;
            color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(225, 126, 16, 0.2);
        }

        .btn-accent:hover,
        .btn-accent:focus {
            background-color: var(--brand-accent-hover) !important;
            border-color: var(--brand-accent-hover) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(225, 126, 16, 0.35) !important;
        }

        /* Outline Primary Button */
        .btn-outline-primary {
            background-color: #ffffff;
            color: var(--brand-primary) !important;
            border: 1px solid var(--brand-primary) !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(134, 20, 8, 0.25);
        }

        /* Utility Accents */
        .text-primary {
            color: var(--brand-primary) !important;
        }

        .text-brand {
            color: var(--brand-accent) !important;
        }

        .bg-brand {
            background: var(--brand-gradient);
            position: relative;
        }

        .section-tag {
            letter-spacing: 0.08em;
            font-size: 0.75rem;
            background-color: var(--brand-accent-light);
            border: 1px solid rgba(225, 126, 16, 0.3);
            color: var(--brand-accent) !important;
            padding: 0.4rem 0.85rem;
            border-radius: 2rem;
            display: inline-block;
        }

        /* Interactive System Cards */
        .system-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1.25rem !important;
            border: 1px solid var(--card-border) !important;
            background: #ffffff;
        }

        .system-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(134, 20, 8, 0.1), 0 8px 10px -6px rgba(134, 20, 8, 0.04) !important;
            border-color: var(--card-hover-border) !important;
        }

        .feature-icon {
            width: 3.25rem;
            height: 3.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            font-size: 1.25rem;
            background-color: var(--brand-primary-light);
            color: var(--brand-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .system-card:hover .feature-icon {
            transform: scale(1.1) rotate(-3deg);
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(134, 20, 8, 0.3);
        }

        /* Global Links */
        a {
            color: var(--brand-primary);
            transition: color 0.15s ease;
        }

        a:hover {
            color: var(--brand-accent);
        }

        p.text-muted {
            color: var(--text-muted) !important;
            line-height: 1.6;
        }

        footer {
            background: var(--brand-gradient) !important;
            position: relative;
            margin-top: auto;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background-color: var(--brand-accent);
        }

        /* Toastr Customizations */
        #toast-container>.toast-success {
            background-color: var(--brand-primary) !important;
        }

        #toast-container>.toast-info {
            background-color: var(--brand-accent) !important;
        }
    </style>
</head>

<body>

    <!-- Navbar Navigation Section -->
    <nav class="navbar navbar-expand-lg sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset('v1/images/unnamed.png') }}" alt="Logo" style="width: 38px; height: auto;">
                <div>
                    <span class="d-block text-dark fw-bold text-uppercase"
                        style="font-size: 0.8rem; letter-spacing: 0.05em;">Division Management</span>
                    <span class="d-block text-brand font-sans" style="font-size: 0.85rem; fw-bold">Information
                        System</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    @auth
                        <!-- Logged In View -->
                        <a href="{{ route('logout') }}" class="btn btn-outline-primary px-3"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Log Out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endauth

                    @guest
                        <!-- Guest View -->
                        <a href="{{ route('login') }}" class="btn btn-outline-primary px-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Log In
                        </a>

                        <a href="/privacy-notice" class="btn btn-accent px-3">
                            Request Account <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Systems & Portals Directory Segment -->
    <section id="systems" class="py-5 flex-grow-1">
        <div class="container py-4">
            <div class="text-center mb-5 mx-auto" style="max-width: 620px;">
                <span class="section-tag fw-bold text-uppercase mb-3">Application Directory</span>
                <h2 class="fw-bold text-dark display-6 mb-3" style="letter-spacing: -0.03em;">Available Information
                    Portals</h2>
                <p class="text-muted fs-6">Select an enterprise portal platform below to access core operational
                    modules, management dashboards, or specialized division profiles.</p>
            </div>

            <div class="row g-4 justify-content-center">
                {{-- System Card 1: HRIS --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card system-card h-100 shadow-sm p-2">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-4">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <h5 class="fw-bold text-dark card-title mb-0">HRIS</h5>
                            </div>
                            <p class="text-muted small flex-grow-1 mb-4">
                                Human Resource Information System for handling profile registrations, service logs, and
                                authorization tokens securely.
                            </p>
                            <a href="#"
                                class="btn btn-outline-primary w-100 py-2.5 mt-auto d-flex align-items-center justify-content-center gap-2">
                                Enter Portal <i class="fas fa-chevron-right small"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System Card 2: Document Tracking --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card system-card h-100 shadow-sm p-2">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-4">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h5 class="fw-bold text-dark card-title mb-0">Document Tracking</h5>
                            </div>
                            <p class="text-muted small flex-grow-1 mb-4">
                                Route communications, manage digital assets safely, and track document checkpoint
                                progress in real-time.
                            </p>
                            <a href="/dts"
                                class="btn btn-outline-primary w-100 py-2.5 mt-auto d-flex align-items-center justify-content-center gap-2">
                                Enter Portal <i class="fas fa-chevron-right small"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System Card 3: DCP Portal --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card system-card h-100 shadow-sm p-2">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-4">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-desktop"></i>
                                </div>
                                <h5 class="fw-bold text-dark card-title mb-0">DCP Portal</h5>
                            </div>
                            <p class="text-muted small flex-grow-1 mb-4">
                                DepEd Computerization Program inventory monitor, system unit allocations, and electronic
                                logging frameworks.
                            </p>
                            <a href="#"
                                class="btn btn-outline-primary w-100 py-2.5 mt-auto d-flex align-items-center justify-content-center gap-2">
                                Enter Portal <i class="fas fa-chevron-right small"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System Card 4: OKD Portal --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card system-card h-100 shadow-sm p-2">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-4">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <h5 class="fw-bold text-dark card-title mb-0">OKD Portal</h5>
                            </div>
                            <p class="text-muted small flex-grow-1 mb-4">
                                Oplan Kalusugan sa DepEd portal containing targeted physical evaluation statistics,
                                healthcare profiles, and matrix parameters.
                            </p>
                            <a href="#"
                                class="btn btn-outline-primary w-100 py-2.5 mt-auto d-flex align-items-center justify-content-center gap-2">
                                Enter Portal <i class="fas fa-chevron-right small"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System Card 5: SGOD Workspace --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card system-card h-100 shadow-sm p-2">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-4">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h5 class="fw-bold text-dark card-title mb-0">SGOD Workspace</h5>
                            </div>
                            <p class="text-muted small flex-grow-1 mb-4">
                                School Governance and Operations Division operational planning workspace, dynamic
                                indicators, and matrix summaries.
                            </p>
                            <a href="#"
                                class="btn btn-outline-primary w-100 py-2.5 mt-auto d-flex align-items-center justify-content-center gap-2">
                                Enter Portal <i class="fas fa-chevron-right small"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System Card 6: Client Satisfactory System --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card system-card h-100 shadow-sm p-2">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-4">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="fw-bold text-dark card-title mb-0">Client Satisfactory System</h5>
                            </div>
                            <p class="text-muted small flex-grow-1 mb-4">
                                Client Satisfactory System for visitors with important matters in the office.
                            </p>
                            <a href="#"
                                class="btn btn-outline-primary w-100 py-2.5 mt-auto d-flex align-items-center justify-content-center gap-2">
                                Enter Portal <i class="fas fa-chevron-right small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Base Global Footer -->
    <footer class="text-white py-4">
        <div class="container text-center">
            <p class="small text-white-50 mb-1">&copy; {{ date('Y') }} DRMS Security Portal Platform. All Rights
                Reserved.</p>
            <p class="small text-white-50 opacity-50 mb-0" style="font-size: 0.75rem;">
                Powered securely with Laravel optimization frameworks and interactive interface controllers.
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
