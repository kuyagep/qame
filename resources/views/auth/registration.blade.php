@extends('layouts.auth-centered')


@section('title', 'Portal Registration')

@section('content')
    <div class="card rounded border-1">
        <div class="card-body p-3 p-sm-4 p-md-5 mx-auto" style="width: 100%;">

            <h2 class="fw-bold mb-2 text-center fs-3">
                Portal Registration
            </h2>

            <p class="text-muted mb-4 text-center small">
                Please provide accurate data in compliance with official record-keeping standards.
            </p>

            {{-- Wizard Progress Bar --}}
            <div class="position-relative mb-5 px-2">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" id="wizard-progress" role="progressbar" style="width: 0%;" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between position-absolute top-50 start-0 translate-middle-y w-100 px-1">
                    <button type="button"
                        class="btn btn-sm btn-primary rounded-pill step-indicator fw-bold d-flex align-items-center justify-content-center"
                        style="width: 2.2rem; height: 2.2rem; min-width: 2.2rem;">1</button>
                    <button type="button"
                        class="btn btn-sm btn-secondary rounded-pill step-indicator fw-bold d-flex align-items-center justify-content-center"
                        style="width: 2.2rem; height: 2.2rem; min-width: 2.2rem;">2</button>
                    <button type="button"
                        class="btn btn-sm btn-secondary rounded-pill step-indicator fw-bold d-flex align-items-center justify-content-center"
                        style="width: 2.2rem; height: 2.2rem; min-width: 2.2rem;">3</button>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" id="wizard-form">
                @csrf

                {{-- STEP 1: Personal & Institutional Information --}}
                <div class="wizard-step">
                    <h5 class="text-primary mb-3 fw-bold border-bottom pb-2 fs-6">1. Personal Details</h5>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-3 col-md-2">
                            <label class="form-label small text-muted mb-1">Prefix</label>
                            <select name="prefix" class="form-select @error('prefix') is-invalid @enderror" required
                                autofocus>
                                <option value="" selected disabled>-</option>
                                <option value="Mr." {{ old('prefix') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Ms." {{ old('prefix') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                <option value="Mrs." {{ old('prefix') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            </select>
                            @error('prefix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-4 col-md-5">
                            <label class="form-label small text-muted mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                placeholder="e.g., Dela Cruz" class="form-control @error('last_name') is-invalid @enderror"
                                required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-5 col-md-5">
                            <label class="form-label small text-muted mb-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g., Juan"
                                class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-4">
                            <label class="form-label small text-muted mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                placeholder="e.g., Santos" class="form-control @error('middle_name') is-invalid @enderror">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-2">
                            <label class="form-label small text-muted mb-1">Suffix</label>
                            <input type="text" name="suffix" value="{{ old('suffix') }}" placeholder="Jr"
                                class="form-control @error('suffix') is-invalid @enderror">
                            @error('suffix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-3">
                            <label class="form-label small text-muted mb-1">Birthdate</label>
                            <input type="date" name="birthdate" value="{{ old('birthdate') }}"
                                class="form-control @error('birthdate') is-invalid @enderror" required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-3">
                            <label class="form-label small text-muted mb-1">Sex</label>
                            <select name="sex" class="form-select @error('sex') is-invalid @enderror" required>
                                <option value="" selected disabled>-</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    {{-- Institutional Details: Position, Department & Office --}}
                    <h5 class="text-primary mt-4 mb-3 fw-bold border-bottom pb-2 fs-6">Institutional Information</h5>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label small text-muted mb-1">Position / Designation</label>
                            <input type="text" name="position" value="{{ old('position') }}"
                                class="form-control @error('position') is-invalid @enderror"
                                placeholder="e.g., Administrative Assistant II" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label small text-muted mb-1">Department</label>
                            <select name="department_id" id="department-select"
                                class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Select Department</option>
                                @foreach ($departments ?? [] as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label small text-muted mb-1">Office / Division</label>
                            <select name="office_id" id="office-select"
                                class="form-select @error('office_id') is-invalid @enderror" required disabled>
                                <option value="" selected disabled>Select Department first</option>
                            </select>
                            @error('office_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-4">
                            <label class="form-label small text-muted mb-1">Religion</label>
                            <input type="text" name="religion" value="{{ old('religion') }}"
                                placeholder="e.g., Roman Catholic"
                                class="form-control @error('religion') is-invalid @enderror" required>
                            @error('religion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-4">
                            <label class="form-label small text-muted mb-1">Disability Status</label>
                            <select name="disability" class="form-select @error('disability') is-invalid @enderror">
                                <option value="No Data" {{ old('disability') == 'No Data' ? 'selected' : '' }}>No Data
                                </option>
                                <option value="No" {{ old('disability', 'No') == 'No' ? 'selected' : '' }}>No</option>
                                <option value="Yes" {{ old('disability') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('disability')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-4">
                            <label class="form-label small text-muted mb-1">Ethnic Group</label>
                            <input list="ethnic_groups" name="ethnic_group" value="{{ old('ethnic_group') }}"
                                class="form-control @error('ethnic_group') is-invalid @enderror"
                                placeholder="Type or select an option...">
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
                    <h5 class="text-primary mb-3 fw-bold border-bottom pb-2 fs-6">2. Address Details</h5>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">Province</label>
                            <select name="province_id" id="province-select"
                                class="form-select @error('province_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Select Province</option>
                                @foreach ($provinces ?? [] as $province)
                                    <option value="{{ $province->province_id }}"
                                        {{ old('province_id') == $province->province_id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">City / Municipality</label>
                            <select name="city_id" id="city-select"
                                class="form-select @error('city_id') is-invalid @enderror" required disabled>
                                <option value="" selected disabled>Select City/Municipality first</option>
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">Barangay</label>
                            <select name="barangay_id" id="barangay-select"
                                class="form-select @error('barangay_id') is-invalid @enderror" required disabled>
                                <option value="" selected disabled>Select Barangay first</option>
                            </select>
                            @error('barangay_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">Purok / Zone / Street</label>
                            <input type="text" name="street" value="{{ old('street') }}"
                                class="form-control @error('street') is-invalid @enderror"
                                placeholder="e.g., Purok 3, Roxas St." required>
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- STEP 3: Account Credentials --}}
                <div class="wizard-step d-none">
                    <h5 class="text-primary mb-3 fw-bold border-bottom pb-2 fs-6">3. Account Credentials</h5>

                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="Enter a valid email address"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Mobile Number (PH)</label>
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <input type="tel" name="mobile_number" value="{{ old('mobile_number') }}"
                                class="form-control @error('mobile_number') is-invalid @enderror"
                                placeholder="9123456789" pattern="9\d{9}"
                                title="Please enter a valid 10-digit PH mobile number starting with 9" required>
                        </div>
                        @error('mobile_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1" for="password">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" value="{{ old('password') }}"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                <span class="input-group-text cursor-pointer" id="toggle-pwd">
                                    <i class="fas fa-eye-slash text-muted" id="icon-pwd"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            {{-- Real-time Validation Checklist --}}
                            <div class="mt-2 p-2 bg-light rounded border small" id="password-requirements"
                                style="font-size: 0.75rem;">
                                <div class="text-muted fw-bold mb-1">Password must contain:</div>
                                <div id="req-length" class="text-danger"><i class="fas fa-times-circle me-1"></i> At
                                    least 8 characters</div>
                                <div id="req-uppercase" class="text-danger"><i class="fas fa-times-circle me-1"></i> One
                                    uppercase letter (A-Z)</div>
                                <div id="req-lowercase" class="text-danger"><i class="fas fa-times-circle me-1"></i> One
                                    lowercase letter (a-z)</div>
                                <div id="req-number" class="text-danger"><i class="fas fa-times-circle me-1"></i> One
                                    number (0-9)</div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1" for="password_confirmation">Confirm
                                Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>
                                <span class="input-group-text cursor-pointer" id="toggle-confirm">
                                    <i class="fas fa-eye-slash text-muted" id="icon-confirm"></i>
                                </span>
                            </div>
                            <div id="match-feedback" class="small mt-1 d-none" style="font-size: 0.75rem;"></div>
                        </div>
                    </div>

                    <div class="form-check mb-4 text-start">
                        <input class="form-check-input cursor-pointer @error('agree_terms') is-invalid @enderror"
                            type="checkbox" name="agree_terms" id="agree_terms" required>
                        <label class="form-check-label small text-muted cursor-pointer fw-bold" for="agree_terms">
                            I agree to the <a href="{{ route('privacy.notice') }}" target="_blank"
                                class="text-decoration-none">Data Privacy</a> and <a href="#"
                                class="text-decoration-none">Terms of Service</a>.
                        </label>
                        @error('agree_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Wizard Navigation Controls --}}
                <div class="d-flex flex-row justify-content-between mt-4 border-top pt-3 gap-2">
                    <button type="button" class="btn btn-sm btn-secondary fw-bold px-3 px-sm-4 invisible"
                        id="prev-btn">
                        <i class="fas fa-arrow-left me-1 me-sm-2"></i> Prev
                    </button>
                    <button type="button" class="btn btn-sm btn-primary fw-bold px-3 px-sm-4" id="next-btn">
                        Next <i class="fas fa-arrow-right ms-1 ms-sm-2"></i>
                    </button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold px-3 px-sm-4 d-none" id="submit-btn">
                        <i class="fas fa-user-plus me-1 me-sm-2"></i> Register
                    </button>
                </div>

            </form>

            <div class="text-center mt-4 small">
                Already have an account?
                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login</a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.wizard-step');
            const indicators = document.querySelectorAll('.step-indicator');
            const progressBar = document.getElementById('wizard-progress');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const form = document.getElementById('wizard-form');

            let currentStep = 0;

            function updateWizard() {
                // Show/hide correct section fields
                steps.forEach((step, idx) => {
                    step.classList.toggle('d-none', idx !== currentStep);
                });

                // Update visual round pill steps
                indicators.forEach((indicator, idx) => {
                    if (idx <= currentStep) {
                        indicator.classList.replace('btn-secondary', 'btn-primary');
                    } else {
                        indicator.classList.replace('btn-primary', 'btn-secondary');
                    }
                });

                // Update thin connecting progress bar line
                const progressPercentage = (currentStep / (steps.length - 1)) * 100;
                progressBar.style.width = `${progressPercentage}%`;

                // Handle conditional visibility of Action Control Buttons
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

            updateWizard();
        });

        // Cascading Selection: Department -> Office
        const deptSelect = document.getElementById('department-select');
        const officeSelect = document.getElementById('office-select');

        deptSelect.addEventListener('change', function() {
            const deptId = this.value;
            officeSelect.disabled = true;
            officeSelect.innerHTML = '<option value="" selected disabled>Loading offices...</option>';

            fetch(`/api/offices?department_id=${deptId}`)
                .then(res => res.json())
                .then(data => {
                    officeSelect.innerHTML = '<option value="" selected disabled>Select Office</option>';
                    data.forEach(office => {
                        officeSelect.innerHTML +=
                            `<option value="${office.id}">${office.name}</option>`;
                    });
                    officeSelect.disabled = false;
                })
                .catch(() => {
                    officeSelect.innerHTML =
                        '<option value="" selected disabled>Error loading offices</option>';
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province-select');
            const citySelect = document.getElementById('city-select');
            const barangaySelect = document.getElementById('barangay-select');

            // Get the CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // 1. When Province Changes -> Fetch Cities
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;

                citySelect.disabled = true;
                barangaySelect.disabled = true;
                citySelect.innerHTML = '<option value="" selected disabled>Loading cities...</option>';
                barangaySelect.innerHTML =
                    '<option value="" selected disabled>Select Barangay first</option>';

                fetch(`/address/cities/${provinceId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // Added CSRF Token
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // console.log('Fetched Cities:', data);
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

            // 2. When City Changes -> Fetch Barangays
            citySelect.addEventListener('change', function() {
                const cityId = this.value;

                barangaySelect.disabled = true;
                barangaySelect.innerHTML =
                    '<option value="" selected disabled>Loading barangays...</option>';

                fetch(`/address/barangays/${cityId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // Added CSRF Token
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

        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const matchFeedback = document.getElementById('match-feedback');

            // Requirements nodes
            const reqLength = document.getElementById('req-length');
            const reqUppercase = document.getElementById('req-uppercase');
            const reqLowercase = document.getElementById('req-lowercase');
            const reqNumber = document.getElementById('req-number');

            // Helper function to flag requirement status
            function setStatus(element, isValid) {
                if (isValid) {
                    element.classList.remove('text-danger');
                    element.classList.add('text-success');
                    element.querySelector('i').className = 'fas fa-check-circle me-1';
                } else {
                    element.classList.remove('text-success');
                    element.classList.add('text-danger');
                    element.querySelector('i').className = 'fas fa-times-circle me-1';
                }
            }

            // Evaluate strength requirements in real-time
            password.addEventListener('input', function() {
                const val = password.value;

                setStatus(reqLength, val.length >= 8);
                setStatus(reqUppercase, /[A-Z]/.test(val));
                setStatus(reqLowercase, /[a-z]/.test(val));
                setStatus(reqNumber, /\d/.test(val));

                checkMatch();
            });

            // Verify confirmation alignment
            function checkMatch() {
                if (!confirmPassword.value) {
                    matchFeedback.className = 'small mt-1 d-none';
                    return;
                }

                matchFeedback.classList.remove('d-none');
                if (password.value === confirmPassword.value) {
                    matchFeedback.className = 'small mt-1 text-success';
                    matchFeedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> Passwords match';
                    confirmPassword.classList.remove('is-invalid');
                    confirmPassword.classList.add('is-valid');
                } else {
                    matchFeedback.className = 'small mt-1 text-danger';
                    matchFeedback.innerHTML = '<i class="fas fa-times-circle me-1"></i> Passwords do not match';
                    confirmPassword.classList.remove('is-valid');
                    confirmPassword.classList.add('is-invalid');
                }
            }

            confirmPassword.addEventListener('input', checkMatch);

            // Password Visibility Toggles
            function setupToggle(buttonId, inputId, iconId) {
                document.getElementById(buttonId).addEventListener('click', function() {
                    const input = document.getElementById(inputId);
                    const icon = document.getElementById(iconId);
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'fas fa-eye text-muted';
                    } else {
                        input.type = 'password';
                        icon.className = 'fas fa-eye-slash text-muted';
                    }
                });
            }

            setupToggle('toggle-pwd', 'password', 'icon-pwd');
            setupToggle('toggle-confirm', 'password_confirmation', 'icon-confirm');
        });
    </script>
@endpush
