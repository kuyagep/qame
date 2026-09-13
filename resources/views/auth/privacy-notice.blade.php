<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Privacy Notice | Master Data Repository</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .privacy-card {
            max-width: 600px;
            border-top: 4px solid #001f3f;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .scrollbox {
            height: 220px;
            overflow-y: scroll;
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            color: #495057;
        }
    </style>
</head>

<body>

    <div class="card privacy-card m-3 bg-white rounded shadow-sm">
        <div class="card-body p-4">
            <div class="text-center mb-3">
                <i class="fas fa-shield-alt text-navy fa-3x" style="color: #001f3f;"></i>
                <h4 class="font-weight-bold text-dark mt-2">Data Privacy & Security Consent Notice</h4>
                <p class="text-muted text-xs">Please review our structural data management rules before creating an
                    profile.</p>
            </div>

            <!-- ENCLOSED PRIVACY SCROLL MATRIX -->
            <div class="scrollbox mb-4">
                <h6 class="font-weight-bold text-dark">1. Collection of System Operational Records</h6>
                <p>To register an institutional profile under the Master Data Repository Framework, we collect explicit
                    identifiers including your full legal identity name, corporate structural email address, and
                    transaction logging parameters.</p>

                <h6 class="font-weight-bold text-dark">2. Purposes of Processing Data</h6>
                <p>Your institutional information data structures are utilized strictly to maintain operational system
                    integrity, execute automated audit trail verifications, provide systemic email dispatch updates upon
                    account activation, and support authorization logging via the Spatie Security Engine layers.</p>

                <h6 class="font-weight-bold text-dark">3. Data Retentions & Governance Closures</h6>
                <p>System operational logs are backed up and securely preserved inside localized database containers
                    matching encrypted environments. Information records will not be redistributed, sold, or exposed to
                    third-party tracking metrics without judicial directives.</p>
            </div>

            <form action="{{ route('privacy.accept') }}" method="POST">
                @csrf
                <div class="custom-control custom-checkbox mb-4 text-left">
                    <input type="checkbox" class="custom-control-input cursor-pointer" id="agree_terms" required>
                    <label class="custom-control-label text-sm font-weight-bold text-dark cursor-pointer"
                        for="agree_terms">
                        I have read, understood, and accept the global data privacy clauses.
                    </label>
                </div>

                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('login') }}"
                            class="btn btn-default btn-sm btn-block font-weight-bold">Decline</a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-sm btn-block font-weight-bold text-white shadow-sm"
                            style="background-color: #001f3f;">
                            Proceed to Register <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
