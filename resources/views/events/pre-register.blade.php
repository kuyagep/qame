@extends('layouts.auth-centered')

@section('title', 'Event Pre-Registration')

@section('content')
    <div class="card rounded border-1">
        <div class="card-body p-3 p-sm-4 p-md-5 mx-auto" style="width: 100%;">
            {{-- General Error Alert (from withErrors(['error' => ...]) or withErrors(['event' => ...])) --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                        <div>
                            @if ($errors->has('error'))
                                <strong>Error:</strong> {{ $errors->first('error') }}
                            @elseif ($errors->has('event'))
                                <strong>Capacity Reached:</strong> {{ $errors->first('event') }}
                            @else
                                <strong>Please check the form for errors:</strong>
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <h2 class="fw-bold mb-2 text-center fs-3">
                Event Pre-Registration
            </h2>

            <!-- Event Details Banner Card -->
            <div class="card bg-light border-1 mb-4">
                <div class="card-body p-3">
                    <h5 class="fw-bold text-dark mb-2">{{ $event->title }}</h5>
                    <div class="d-flex flex-column flex-sm-row justify-content-between small text-muted">
                        <div class="mb-1 mb-sm-0">
                            <i class="far fa-clock text-primary me-1"></i> {{ $event->formatted_date_range }}
                        </div>
                        <div>
                            <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $event->location }}
                        </div>
                    </div>
                </div>
            </div>

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

            <form method="POST" action="{{ route('events.pre-register.store', $event->join_code) }}" id="wizard-form">
                @csrf

                {{-- STEP 1: Personal & Institutional Details --}}
                <div class="wizard-step">
                    <h5 class="text-primary mb-3 fw-bold border-bottom pb-2 fs-6">1. Personal Details</h5>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g., Juan"
                                class="form-control @error('first_name') is-invalid @enderror" required autofocus>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                placeholder="e.g., Dela Cruz" class="form-control @error('last_name') is-invalid @enderror"
                                required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">Sex</label>
                            <select name="sex" id="sex" class="form-select @error('sex') is-invalid @enderror"
                                required>
                                <option value="" selected disabled>-- Select Sex --</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted mb-1">Position / Designation</label>
                            <input type="text" name="position" value="{{ old('position') }}"
                                class="form-control @error('position') is-invalid @enderror"
                                placeholder="e.g., Teacher III / AO II" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="text-primary mt-4 mb-3 fw-bold border-bottom pb-2 fs-6">Institutional Information</h5>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6">
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

                        <div class="col-12 col-md-6">
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

                {{-- STEP 3: Contact & Confirmation --}}
                <div class="wizard-step d-none">
                    <h5 class="text-primary mb-3 fw-bold border-bottom pb-2 fs-6">3. Contact Details & Privacy</h5>

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

                    <div class="form-check mb-4 text-start">
                        <input class="form-check-input cursor-pointer @error('agree_terms') is-invalid @enderror"
                            type="checkbox" name="agree_terms" id="agree_terms" required>
                        <label class="form-check-label small text-muted cursor-pointer fw-bold" for="agree_terms">
                            I agree to the <a href="{{ route('privacy.notice') }}" target="_blank"
                                class="text-decoration-none">Data Privacy Notice</a> and terms of event participation.
                        </label>
                        @error('agree_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Wizard Controls --}}
                <div class="d-flex flex-row justify-content-between mt-4 border-top pt-3 gap-2">
                    <button type="button" class="btn btn-sm btn-secondary fw-bold px-3 px-sm-4 invisible"
                        id="prev-btn">
                        <i class="fas fa-arrow-left me-1 me-sm-2"></i> Prev
                    </button>
                    <button type="button" class="btn btn-sm btn-primary fw-bold px-3 px-sm-4" id="next-btn">
                        Next <i class="fas fa-arrow-right ms-1 ms-sm-2"></i>
                    </button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold px-3 px-sm-4 d-none" id="submit-btn">
                        <i class="fas fa-user-plus me-1 me-sm-2"></i> Register & Join Event
                    </button>
                </div>

            </form>

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

            let currentStep = 0;

            function updateWizard() {
                steps.forEach((step, idx) => {
                    step.classList.toggle('d-none', idx !== currentStep);
                });

                indicators.forEach((indicator, idx) => {
                    if (idx <= currentStep) {
                        indicator.classList.replace('btn-secondary', 'btn-primary');
                    } else {
                        indicator.classList.replace('btn-primary', 'btn-secondary');
                    }
                });

                const progressPercentage = (currentStep / (steps.length - 1)) * 100;
                progressBar.style.width = `${progressPercentage}%`;

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

            // Cascading Selection: Department -> Office
            const deptSelect = document.getElementById('department-select');
            const officeSelect = document.getElementById('office-select');

            if (deptSelect && officeSelect) {
                deptSelect.addEventListener('change', function() {
                    const deptId = this.value;
                    officeSelect.disabled = true;
                    officeSelect.innerHTML =
                        '<option value="" selected disabled>Loading offices...</option>';

                    fetch(`/api/offices?department_id=${deptId}`)
                        .then(res => res.json())
                        .then(data => {
                            officeSelect.innerHTML =
                                '<option value="" selected disabled>Select Office</option>';
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
            }

            // Cascading Selection: Address (Province -> City -> Barangay)
            const provinceSelect = document.getElementById('province-select');
            const citySelect = document.getElementById('city-select');
            const barangaySelect = document.getElementById('barangay-select');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (provinceSelect) {
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
            }

            if (citySelect) {
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
            }
        });
    </script>
@endpush
