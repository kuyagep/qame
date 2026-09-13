@extends('layouts.main')

@section('title', 'My Profile')
@section('content-header', 'My Profile')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            {{-- Session Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="icon fas fa-check mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                {{-- LEFT COLUMN: PROFILE CARD --}}
                <div class="col-12 col-lg-4">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-body box-profile">
                            <div class="text-center position-relative mb-3">
                                <img id="avatar-preview" class="profile-user-img img-fluid img-circle shadow-sm"
                                    style="width: 128px; height: 128px; object-fit: cover;"
                                    src="{{ route('users.avatar', auth()->id()) }}" alt="Profile Avatar">
                            </div>

                            <h3 class="profile-username text-center font-weight-bold mb-1">
                                {{ auth()->user()->name }}
                            </h3>

                            <p class="text-muted text-center mb-1 small">
                                <i class="fas fa-envelope mr-1"></i> {{ auth()->user()->email }}
                            </p>

                            <div class="text-center mb-3">
                                <span class="badge badge-light border">@ {{ auth()->user()->username }}</span>
                            </div>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-id-badge mr-2 text-muted"></i><b>Employee ID</b></span>
                                    <span class="text-muted">{{ auth()->user()->employee_id ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span><i class="fas fa-building mr-2 text-muted"></i><b>Assigned Office</b></span>
                                        <span class="text-muted font-weight-bold">
                                            {{ auth()->user()->office->name ?? 'Unassigned' }}
                                        </span>
                                    </div>
                                    @if (auth()->user()->office)
                                        <div class="text-right">
                                            <span
                                                class="badge badge-secondary mb-1">{{ auth()->user()->office->department->name }}</span>

                                        </div>
                                    @endif
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user-shield mr-2 text-muted"></i><b>Account Status</b></span>
                                    <span
                                        class="badge badge-{{ auth()->user()->status === 'active' ? 'success' : (auth()->user()->status === 'pending' ? 'warning' : 'danger') }} px-2 py-1">
                                        {{ ucfirst(auth()->user()->status) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-phone mr-2 text-muted"></i><b>Mobile Number</b></span>
                                    <span class="text-muted">{{ auth()->user()->mobile_number ?? 'Not Provided' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <div class="mb-2"><i class="fas fa-user-tag mr-2 text-muted"></i><b>Roles</b></div>
                                    <div>
                                        @forelse (auth()->user()->roles as $role)
                                            <span class="badge badge-info mr-1 mb-1">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-muted small">No roles assigned</span>
                                        @endforelse
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: FORMS --}}
                <div class="col-12 col-lg-8">

                    {{-- Update Profile Details --}}
                    <div class="card card-outline card-primary shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-user-edit mr-2"></i>Update Profile
                                Details</h3>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                {{-- SECTION: NAME BREAKDOWN --}}
                                <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">
                                    <i class="fas fa-signature mr-1"></i> Name Details
                                </h6>
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-2 form-group">
                                        <label class="form-label small text-muted mb-1" for="prefix">Prefix</label>
                                        <input type="text" name="prefix" id="prefix"
                                            class="form-control @error('prefix') is-invalid @enderror"
                                            value="{{ old('prefix', auth()->user()->prefix) }}" placeholder="Mr., Ms.">
                                        @error('prefix')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 form-group">
                                        <label class="form-label small text-muted mb-1" for="first_name">First Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="first_name" id="first_name"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            value="{{ old('first_name', auth()->user()->first_name) }}" required>
                                        @error('first_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 form-group">
                                        <label class="form-label small text-muted mb-1" for="middle_name">Middle
                                            Name</label>
                                        <input type="text" name="middle_name" id="middle_name"
                                            class="form-control @error('middle_name') is-invalid @enderror"
                                            value="{{ old('middle_name', auth()->user()->middle_name) }}">
                                        @error('middle_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 form-group">
                                        <label class="form-label small text-muted mb-1" for="last_name">Last Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="last_name" id="last_name"
                                            class="form-control @error('last_name') is-invalid @enderror"
                                            value="{{ old('last_name', auth()->user()->last_name) }}" required>
                                        @error('last_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 form-group">
                                        <label class="form-label small text-muted mb-1" for="suffix">Suffix</label>
                                        <input type="text" name="suffix" id="suffix"
                                            class="form-control @error('suffix') is-invalid @enderror"
                                            value="{{ old('suffix', auth()->user()->suffix) }}" placeholder="Jr., III">
                                        @error('suffix')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- SECTION: AUTH & DETAILS --}}
                                <h6 class="text-primary font-weight-bold border-bottom pb-2 mt-3 mb-3">
                                    <i class="fas fa-info-circle mr-1"></i> Account & Personal Info
                                </h6>
                                <div class="row">
                                    <div class="col-12 col-md-6 form-group">
                                        <label class="form-label small text-muted mb-1" for="username">Username <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="username" id="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username', auth()->user()->username) }}" required>
                                        @error('username')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <label class="form-label small text-muted mb-1" for="email">Email
                                            Address</label>
                                        <input type="email" id="email" class="form-control bg-light"
                                            value="{{ auth()->user()->email }}" disabled>
                                        <small class="form-text text-muted">Email cannot be modified directly. Contact
                                            system administrator.</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-4 form-group">
                                        <label class="form-label small text-muted mb-1" for="sex">Gender</label>
                                        <select name="sex" id="sex"
                                            class="form-control @error('sex') is-invalid @enderror">
                                            <option value="">Select Gender</option>
                                            <option value="Male"
                                                {{ old('sex', auth()->user()->sex) === 'Male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="Female"
                                                {{ old('sex', auth()->user()->sex) === 'Female' ? 'selected' : '' }}>
                                                Female</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-4 form-group">
                                        <label class="form-label small text-muted mb-1" for="mobile_number">Mobile
                                            Number</label>
                                        <input type="text" name="mobile_number" id="mobile_number"
                                            class="form-control @error('mobile_number') is-invalid @enderror"
                                            value="{{ old('mobile_number', auth()->user()->mobile_number) }}"
                                            placeholder="09XXXXXXXXX">
                                        @error('mobile_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-4 form-group">
                                        <label class="form-label small text-muted mb-1" for="birthdate">Birthdate</label>
                                        <input type="date" name="birthdate" id="birthdate"
                                            class="form-control @error('birthdate') is-invalid @enderror"
                                            value="{{ old('birthdate', auth()->user()->birthdate ? \Carbon\Carbon::parse(auth()->user()->birthdate)->format('Y-m-d') : '') }}">
                                        @error('birthdate')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-4 form-group">
                                        <label class="form-label small text-muted mb-1" for="religion">Religion</label>
                                        <input type="text" name="religion" id="religion"
                                            class="form-control @error('religion') is-invalid @enderror"
                                            value="{{ old('religion', auth()->user()->religion) }}">
                                        @error('religion')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-4 form-group">
                                        <label class="form-label small text-muted mb-1" for="disability">Disability
                                            Status</label>
                                        <input type="text" name="disability" id="disability"
                                            class="form-control @error('disability') is-invalid @enderror"
                                            value="{{ old('disability', auth()->user()->disability) }}"
                                            placeholder="None / Specify">
                                        @error('disability')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-4 form-group">
                                        <label class="form-label small text-muted mb-1" for="ethnic_group">Ethnic
                                            Group</label>
                                        <input type="text" name="ethnic_group" id="ethnic_group"
                                            class="form-control @error('ethnic_group') is-invalid @enderror"
                                            value="{{ old('ethnic_group', auth()->user()->ethnic_group) }}">
                                        @error('ethnic_group')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label class="form-label small text-muted mb-1" for="avatar">Change Avatar
                                            Image</label>
                                        <div class="custom-file">
                                            <input type="file" name="avatar" id="avatar" accept="image/*"
                                                class="custom-file-input @error('avatar') is-invalid @enderror">
                                            <label class="custom-file-label" for="avatar">Choose file...</label>
                                        </div>
                                        @error('avatar')
                                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small text-muted mb-1">Position / Designation</label>
                                        <input type="text" name="position"
                                            value="{{ old('position', auth()->user()->position) }}"
                                            class="form-control @error('position') is-invalid @enderror"
                                            placeholder="e.g., Administrative Assistant II" required>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label small text-muted mb-1">Department</label>
                                        <select name="department_id" id="department-select"
                                            class="form-control @error('department_id') is-invalid @enderror" required>
                                            <option value="">Select Department</option>
                                            @foreach ($departments ?? [] as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ old('department_id', auth()->user()->office->department_id ?? '') == $department->id ? 'selected' : '' }}>
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
                                            class="form-control @error('office_id') is-invalid @enderror" required
                                            disabled>
                                            <option value="" disabled>Select Department first</option>
                                        </select>
                                        @error('office_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- SECTION: ADDRESS ALIGNMENT --}}
                                <h6 class="text-primary font-weight-bold border-bottom pb-2 mt-3 mb-3">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Address Details
                                </h6>
                                <div class="row">
                                    <div class="col-12 col-sm-4 form-group">
                                        <label for="province-select" class="small text-muted mb-1">Province</label>
                                        <select name="province_id" id="province-select"
                                            class="form-control @error('province_id') is-invalid @enderror">
                                            <option value="" selected disabled>Select Province</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_id }}"
                                                    {{ old('province_id', auth()->user()->province_id) == $province->province_id ? 'selected' : '' }}>
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('province_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-sm-4 form-group">
                                        <label for="city-select" class="small text-muted mb-1">City / Municipality</label>
                                        <select name="city_id" id="city-select"
                                            class="form-control @error('city_id') is-invalid @enderror" disabled>
                                            <option value="" selected disabled>Select City/Municipality</option>
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-sm-4 form-group">
                                        <label for="barangay-select" class="small text-muted mb-1">Barangay</label>
                                        <select name="barangay_id" id="barangay-select"
                                            class="form-control @error('barangay_id') is-invalid @enderror" disabled>
                                            <option value="" selected disabled>Select Barangay</option>
                                        </select>
                                        @error('barangay_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group mb-0">
                                        <label class="form-label small text-muted mb-1" for="purok">Purok / Zone /
                                            Street</label>
                                        <input type="text" name="street" id="street"
                                            value="{{ old('street', auth()->user()->street) }}"
                                            class="form-control @error('street') is-invalid @enderror"
                                            placeholder="e.g., Purok 3, Roxas St.">
                                        @error('street')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right bg-light">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save mr-1"></i> Save Profile Details
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Change Password --}}
                    <div class="card card-outline card-warning shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-lock mr-2"></i>Change Password</h3>
                        </div>

                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1" for="current_password">Current
                                        Password <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 form-group">
                                        <label class="form-label small text-muted mb-1" for="password">New Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 form-group mb-0">
                                        <label class="form-label small text-muted mb-1"
                                            for="password_confirmation">Confirm New Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right bg-light">
                                <button type="submit" class="btn btn-warning font-weight-bold px-4">
                                    <i class="fas fa-key mr-1"></i> Change Password
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Permissions --}}
                    <div class="card card-outline card-secondary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-user-shield mr-2"></i>Assigned System
                                Permissions</h3>
                        </div>
                        <div class="card-body">
                            @forelse (auth()->user()->getAllPermissions() as $permission)
                                <span class="badge badge-success mb-2 py-2 px-3 font-weight-normal">
                                    <i class="fas fa-check-circle mr-1"></i> {{ $permission->name }}
                                </span>
                            @empty
                                <p class="text-muted mb-0"><i class="fas fa-info-circle mr-1"></i> No explicit permissions
                                    assigned directly to this account.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deptSelect = document.getElementById('department-select');
            const officeSelect = document.getElementById('office-select');



            const provinceSelect = document.getElementById('province-select');
            const citySelect = document.getElementById('city-select');
            const barangaySelect = document.getElementById('barangay-select');
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatar-preview');

            // Meta CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Initial values for Edit Profile
            const initialDeptId = "{{ old('department_id', auth()->user()->office->department_id ?? '') }}";
            const initialOfficeId = "{{ old('office_id', auth()->user()->office_id ?? '') }}";

            const initialProvinceId = "{{ old('province_id', auth()->user()->province_id) }}";
            const initialCityId = "{{ old('city_id', auth()->user()->city_id) }}";
            const initialBarangayId = "{{ old('barangay_id', auth()->user()->barangay_id) }}";


            // File Input & Live Preview
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        const fileName = file.name;
                        // Update label
                        const label = this.nextElementSibling;
                        if (label) label.textContent = fileName;
                        // Update Image Preview dynamically
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            if (avatarPreview) avatarPreview.src = evt.target.result;
                        };
                        reader.readAsDataURL(file);

                    }
                });
            }




            // Helper: Fetch Offices
            function fetchOffices(deptId, selectedOfficeId = null) {
                if (!deptId) return;

                officeSelect.disabled = true;
                officeSelect.innerHTML = '<option value="" selected disabled>Loading offices...</option>';

                fetch(`/api/offices?department_id=${deptId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response error');
                        return res.json();
                    })
                    .then(data => {
                        officeSelect.innerHTML =
                            '<option value="" selected disabled>Select Office</option>';
                        data.forEach(office => {
                            const selected = (selectedOfficeId && selectedOfficeId == office.id) ?
                                'selected' : '';
                            officeSelect.innerHTML +=
                                `<option value="${office.id}" ${selected}>${office.name}</option>`;
                        });
                        officeSelect.disabled = false;
                    })
                    .catch(() => {
                        officeSelect.innerHTML =
                            '<option value="" selected disabled>Error loading offices</option>';
                    });
            }

            // Event Listener for Dynamic Dropdown
            deptSelect.addEventListener('change', function() {
                fetchOffices(this.value);
            });

            // Initialize pre-selected department cascading options if available
            if (initialDeptId) {
                fetchOffices(initialDeptId, initialOfficeId);
            }


            // Helper: Fetch Cities

            function fetchCities(provinceId, selectedCityId = null) {

                if (!provinceId) return;



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

                    .then(res => {

                        if (!res.ok) throw new Error('Network response error');

                        return res.json();

                    })

                    .then(data => {

                        citySelect.innerHTML =

                            '<option value="" selected disabled>Select City/Municipality</option>';

                        data.forEach(city => {

                            const selected = (selectedCityId && selectedCityId == city.city_id) ?

                                'selected' : '';

                            citySelect.innerHTML +=

                                `<option value="${city.city_id}" ${selected}>${city.name}</option>`;

                        });

                        citySelect.disabled = false;



                        if (selectedCityId) {

                            fetchBarangays(selectedCityId, initialBarangayId);

                        }

                    })

                    .catch(() => {

                        citySelect.innerHTML =

                            '<option value="" selected disabled>Error loading cities</option>';

                    });

            }



            // Helper: Fetch Barangays

            function fetchBarangays(cityId, selectedBarangayId = null) {

                if (!cityId) return;



                barangaySelect.disabled = true;

                barangaySelect.innerHTML = '<option value="" selected disabled>Loading barangays...</option>';



                fetch(`/address/barangays/${cityId}`, {

                        method: 'GET',

                        headers: {

                            'Content-Type': 'application/json',

                            'X-CSRF-TOKEN': csrfToken

                        }

                    })

                    .then(res => {

                        if (!res.ok) throw new Error('Network response error');

                        return res.json();

                    })

                    .then(data => {

                        barangaySelect.innerHTML =

                            '<option value="" selected disabled>Select Barangay</option>';

                        data.forEach(barangay => {

                            const selected = (selectedBarangayId && selectedBarangayId == barangay

                                .id) ? 'selected' : '';

                            barangaySelect.innerHTML +=

                                `<option value="${barangay.id}" ${selected}>${barangay.name}</option>`;

                        });

                        barangaySelect.disabled = false;

                    })

                    .catch(() => {

                        barangaySelect.innerHTML =

                            '<option value="" selected disabled>Error loading barangays</option>';

                    });

            }



            // Event Listeners for Dynamic Dropdowns

            provinceSelect.addEventListener('change', function() {

                fetchCities(this.value);

            });



            citySelect.addEventListener('change', function() {

                fetchBarangays(this.value);

            });



            // Initialize pre-selected address cascading options if available

            if (initialProvinceId) {

                fetchCities(initialProvinceId, initialCityId);

            }

        });
    </script>
@endpush
