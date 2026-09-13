@extends('layouts.auth-centered')

@section('title', 'Privacy Notice')

@section('content')
    <div class="card m-3 bg-white rounded shadow-sm">
        <div class="card-body p-4">
            <div class="text-center mb-3">
                <h4 class="fw-bold text-dark mt-2">Data Privacy Notice</h4>
                <p class="text-muted small">
                    Please review our structural data management rules before creating a profile.
                </p>
            </div>

            <!-- ENCLOSED PRIVACY SCROLL MATRIX -->
            <div class="scrollbox mb-4">
                <h6 class="fw-bold text-dark">1. Collection of Personal & Institutional Data</h6>
                <p>In compliance with Republic Act No. 10173 (Data Privacy Act of 2012) and relevant DepEd guidelines, we
                    collect personal and institutional information necessary to register and maintain your profile. This
                    includes explicit identifiers such as your full legal name, official institutional email address, system
                    transaction logs, and user credentials required for account verification and security management.</p>

                <h6 class="fw-bold text-dark">2. Purpose and Lawful Basis of Processing</h6>
                <p>Your data is processed strictly in accordance with legitimate educational and administrative operations.
                    Processing is conducted to maintain system integrity, execute automated audit trail logging, dispatch
                    account activation notices, manage role-based access controls, and support system authorization in
                    compliance with agency mandates and security policies.</p>

                <h6 class="fw-bold text-dark">3. Data Retention, Protection & Governance</h6>
                <p>All collected information and system logs are stored and encrypted in secure database containers in
                    accordance with government data protection standards. Personal data will not be shared, rented, or
                    disclosed to third parties except as required for official government reporting, or as mandated by court
                    order or applicable Philippine laws. Data retention and disposal shall follow government records
                    management rules and the policies set forth by DepEd.</p>

                <h6 class="fw-bold text-dark">4. Data Subject Rights</h6>
                <p>In accordance with the Data Privacy Act of 2012, registered users retain the right to be informed,
                    access, correct, or request the erasure or blocking of their personal information on reasonable grounds,
                    subject to statutory limits. For privacy inquiries or to exercise your rights, you may contact the
                    designated Data Protection Officer (DPO) or system administrator.</p>
            </div>

            <form action="{{ route('privacy.accept') }}" method="POST">
                @csrf

                {{-- Bootstrap 5 Form Check Syntax --}}
                <div class="form-check mb-4 text-start">
                    <input type="checkbox" class="form-check-input cursor-pointer" id="agree_terms" name="agree_terms"
                        required>
                    <label class="form-check-label small fw-bold text-dark cursor-pointer" for="agree_terms">
                        I have read, understood, and accept the global data privacy clauses.
                    </label>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <a href="/" class="btn btn-sm w-100 fw-bold text-white shadow-sm"
                            style="background-color: #495057;">
                            Decline
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold text-white shadow-sm">
                            Agree <i class="fas fa-arrow-right ms-1 small"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* If your layout already handles alignment, you can drop this section */
        body {
            background-color: #f4f6f9;
        }

        .privacy-card {
            border-top: 4px solid #0033A0;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .scrollbox {
            height: 220px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            color: #495057;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endpush
