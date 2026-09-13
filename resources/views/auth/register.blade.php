@extends('layouts.auth-split')

@section('title', 'Official Government Registration')

@push('styles')
    <style>
        /* Stepper Styling */
        .stepper {
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .stepper::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e9ecef;
            z-index: 1;
        }

        .stepper-progress {
            position: absolute;
            top: 20px;
            left: 0;
            height: 2px;
            background-color: #0d6efd;
            z-index: 1;
            transition: width 0.3s ease;
        }

        .step-item {
            position: relative;
            z-index: 2;
            text-align: center;
            background: transparent;
        }

        .step-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: #ffffff;
            border: 2px solid #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        .step-item.active .step-circle {
            border-color: #0d6efd;
            background-color: #0d6efd;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
        }

        .step-item.completed .step-circle {
            border-color: #198754;
            background-color: #198754;
            color: #ffffff;
        }

        .step-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #6c757d;
            margin-top: 8px;
        }

        .step-item.active .step-label {
            color: #0d6efd;
        }

        .step-item.completed .step-label {
            color: #198754;
        }
    </style>
@endpush

@section('content')
    <div class="w-100 py-4">
        <div class="mx-auto" style="max-width: 720px;">

            <!-- Header Section -->
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark mb-1">Applicant Registration</h2>
                <p class="text-muted small">
                    Please complete all fields below accurately in compliance with official record-keeping standards.
                </p>
            </div>

            <!-- Wizard Stepper Visual Bar -->
            <div class="stepper mb-4 position-relative">
                <div class="stepper-progress" id="wizard-progress" style="width: 0%;"></div>

                <div class="step-item active" data-step="0">
                    <div class="step-circle"><i class="fas fa-user small"></i></div>
                    <div class="step-label d-none d-sm-block">Personal Data</div>
                </div>
                <div class="step-item" data-step="1">
                    <div class="step-circle"><i class="fas fa-map-marker-alt small"></i></div>
                    <div class="step-label d-none d-sm-block">Address Details</div>
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-circle"><i class="fas fa-lock small"></i></div>
                    <div class="step-label d-none d-sm-block">Credentials</div>
                </div>
            </div>

            <!-- Form Wrapper Card -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">

                    <form method="POST" action="{{ route('register') }}" id="wizard-form" novalidate>
                        @csrf

                        {{-- STEP 1: Personal Information --}}
                        <div class="wizard-step">
                            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                                <i class="fas fa-user-circle text-primary me-2 fs-5"></i>
                                <h5 class="card-title mb-0 fw-semibold">1. Personal Information</h5>
                            </div>

                            <!-- Full Name Grid -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-2 col-sm-4">
                                    <label class="form-label small fw-medium text-secondary">Prefix</label>
                                    <select name="prefix"
                                        class="form-select form-select-sm @error('prefix') is-invalid @enderror">
                                        <option value="" selected disabled>-</option>
                                        <option value="Mr." {{ old('prefix') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                        <option value="Ms." {{ old('prefix') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                        <option value="Mrs." {{ old('prefix') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                    </select>
                                    @error('prefix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 col-sm-8">
                                    <label class="form-label small fw-medium text-secondary">
                                        First Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                                        class="form-control form-control-sm @error('first_name') is-invalid @enderror"
                                        required autofocus>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label small fw-medium text-secondary">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                        class="form-control form-control-sm @error('middle_name') is-invalid @enderror">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                                        class="form-control form-control-sm @error('last_name') is-invalid @enderror"
                                        required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-1 col-sm-4">
                                    <label class="form-label small fw-medium text-secondary">Suffix</label>
                                    <input type="text" name="suffix" value="{{ old('suffix') }}" placeholder="Jr."
                                        class="form-control form-control-sm @error('suffix') is-invalid @enderror">
                                    @error('suffix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Details Grid -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light text-muted"><i
                                                class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="name@example.com" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Mobile Number (PH) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light text-muted fw-semibold">+63</span>
                                        <input type="tel" name="mobile_number" value="{{ old('mobile_number') }}"
                                            class="form-control @error('mobile_number') is-invalid @enderror"
                                            placeholder="9123456789" pattern="9\d{9}" required
                                            title="10-digit PH mobile number starting with 9">
                                    </div>
                                    @error('mobile_number')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Personal Demographics -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Birthdate <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="birthdate" value="{{ old('birthdate') }}"
                                        class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                        required>
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">Religion</label>
                                    <input type="text" name="religion" value="{{ old('religion') }}"
                                        placeholder="e.g., Roman Catholic"
                                        class="form-control form-control-sm @error('religion') is-invalid @enderror">
                                    @error('religion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">Disability Status</label>
                                    <select name="disability"
                                        class="form-select form-select-sm @error('disability') is-invalid @enderror">
                                        <option value="No Data" {{ old('disability') == 'No Data' ? 'selected' : '' }}>No
                                            Data</option>
                                        <option value="No" {{ old('disability', 'No') == 'No' ? 'selected' : '' }}>No
                                        </option>
                                        <option value="Yes" {{ old('disability') == 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                    </select>
                                    @error('disability')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">Ethnic Group</label>
                                    <input list="ethnic_groups" name="ethnic_group" value="{{ old('ethnic_group') }}"
                                        class="form-control form-control-sm @error('ethnic_group') is-invalid @enderror"
                                        placeholder="Type or select group...">
                                    <datalist id="ethnic_groups">
                                        <option value="None">
                                        <option value="Mandaya">
                                        <option value="Bicolano">
                                        <option value="Ilocano">
                                        <option value="Cebuano">
                                        <option value="Kalagan">
                                        <option value="Manobo">
                                        <option value="Bisaya">
                                        <option value="Bagobo">
                                        <option value="Tausug">
                                    </datalist>
                                    @error('ethnic_group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2: Address Details --}}
                        <div class="wizard-step d-none">
                            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                                <i class="fas fa-map-marked-alt text-primary me-2 fs-5"></i>
                                <h5 class="card-title mb-0 fw-semibold">2. Residence Address</h5>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Province <span class="text-danger">*</span>
                                    </label>
                                    <select name="province_id" id="province-select"
                                        class="form-select form-select-sm @error('province_id') is-invalid @enderror"
                                        required>
                                        <option value="" selected disabled>Select Province</option>
                                        @foreach ($provinces ?? [] as $province)
                                            <option value="{{ $province->province_id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        City / Municipality <span class="text-danger">*</span>
                                    </label>
                                    <select name="city_id" id="city-select"
                                        class="form-select form-select-sm @error('city_id') is-invalid @enderror" required
                                        disabled>
                                        <option value="" selected disabled>Select Province first</option>
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Barangay <span class="text-danger">*</span>
                                    </label>
                                    <select name="barangay_id" id="barangay-select"
                                        class="form-select form-select-sm @error('barangay_id') is-invalid @enderror"
                                        required disabled>
                                        <option value="" selected disabled>Select City/Municipality first</option>
                                    </select>
                                    @error('barangay_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">Purok / Zone / Street</label>
                                    <input type="text" name="purok" value="{{ old('purok') }}"
                                        class="form-control form-control-sm @error('purok') is-invalid @enderror"
                                        placeholder="e.g., Purok 3, Roxas St.">
                                    @error('purok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- STEP 3: Account Credentials --}}
                        <div class="wizard-step d-none">
                            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                                <i class="fas fa-shield-alt text-primary me-2 fs-5"></i>
                                <h5 class="card-title mb-0 fw-semibold">3. Account Credentials</h5>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-medium text-secondary">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" value="{{ old('username') }}"
                                        class="form-control @error('username') is-invalid @enderror" required
                                        placeholder="Choose a username">
                                </div>
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" name="password" id="password"
                                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                            class="form-control @error('password') is-invalid @enderror" required
                                            placeholder="••••••••">
                                        <button class="btn btn-outline-secondary toggle-password-btn" type="button"
                                            tabindex="-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium text-secondary">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" required placeholder="••••••••">
                                        <button class="btn btn-outline-secondary toggle-password-btn" type="button"
                                            tabindex="-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-text small text-muted mb-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Must be at least 8 characters, containing uppercase, lowercase, and a number.
                            </div>
                        </div>

                        <!-- Wizard Navigation Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-light border btn-sm px-4 invisible" id="prev-btn">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </button>

                            <div>
                                <button type="button" class="btn btn-primary btn-sm px-4 fw-medium" id="next-btn">
                                    Next <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                                <button type="submit" class="btn btn-success btn-sm px-4 fw-medium d-none"
                                    id="submit-btn">
                                    <i class="fas fa-check-circle me-1"></i> Complete Registration
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-4 text-muted small">
                Already registered?
                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Sign In Here</a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.wizard-step');
            const stepItems = document.querySelectorAll('.step-item');
            const progressBar = document.getElementById('wizard-progress');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            let currentStep = 0;

            function updateWizard() {
                // Update Form Step Views
                steps.forEach((step, idx) => {
                    step.classList.toggle('d-none', idx !== currentStep);
                });

                // Update Stepper Progress & Indicators
                stepItems.forEach((item, idx) => {
                    if (idx < currentStep) {
                        item.classList.add('completed');
                        item.classList.remove('active');
                    } else if (idx === currentStep) {
                        item.classList.add('active');
                        item.classList.remove('completed');
                    } else {
                        item.classList.remove('active', 'completed');
                    }
                });

                const progressPercentage = (currentStep / (steps.length - 1)) * 100;
                progressBar.style.width = `${progressPercentage}%`;

                // Button Control Visibility
                prevBtn.classList.toggle('invisible', currentStep === 0);

                if (currentStep === steps.length - 1) {
                    nextBtn.classList.add('d-none');
                    submitBtn.classList.remove('d-none');
                } else {
                    nextBtn.classList.remove('d-none');
                    submitBtn.classList.add('d-none');
                }
            }

            function validateCurrentStep() {
                const inputs = steps[currentStep].querySelectorAll('input, select');
                let valid = true;

                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        valid = false;
                    }
                });
                return valid;
            }

            nextBtn.addEventListener('click', function() {
                if (validateCurrentStep() && currentStep < steps.length - 1) {
                    currentStep++;
                    updateWizard();
                }
            });

            prevBtn.addEventListener('click', function() {
                if (currentStep > 0) {
                    currentStep--;
                    updateWizard();
                }
            });

            // Toggle Eye Button Password Visibility
            const toggleButtons = document.querySelectorAll('.toggle-password-btn');
            toggleButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });

            updateWizard();
        });

        // Dynamic AJAX Address Loading Script
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province-select');
            const citySelect = document.getElementById('city-select');
            const barangaySelect = document.getElementById('barangay-select');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;

                citySelect.disabled = true;
                barangaySelect.disabled = true;
                citySelect.innerHTML = '<option value="" selected disabled>Loading cities...</option>';
                barangaySelect.innerHTML =
                    '<option value="" selected disabled>Select City/Municipality first</option>';

                fetch(`/address/cities/${provinceId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML =
                            '<option value="" selected disabled>Select City/Municipality</option>';
                        data.forEach(city => {
                            citySelect.innerHTML +=
                                `<option value="${city.city_id}">${city.name}</option>`;
                        });
                        citySelect.disabled = false;
                    })
                    .catch(() => {
                        citySelect.innerHTML =
                            '<option value="" selected disabled>Error loading records</option>';
                    });
            });

            citySelect.addEventListener('change', function() {
                const cityId = this.value;

                barangaySelect.disabled = true;
                barangaySelect.innerHTML =
                    '<option value="" selected disabled>Loading barangays...</option>';

                fetch(`/address/barangays/${cityId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        barangaySelect.innerHTML =
                            '<option value="" selected disabled>Select Barangay</option>';
                        data.forEach(barangay => {
                            barangaySelect.innerHTML +=
                                `<option value="${barangay.id}">${barangay.name}</option>`;
                        });
                        barangaySelect.disabled = false;
                    })
                    .catch(() => {
                        barangaySelect.innerHTML =
                            '<option value="" selected disabled>Error loading records</option>';
                    });
            });
        });
    </script>
@endpush
