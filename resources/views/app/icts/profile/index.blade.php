@extends('app.icts.layouts.app')

@section('title', 'My Profile')
@section('content-header', 'My Profile')

{{-- Added structural CSRF meta setup if it isn't defined inside layouts.main --}}
@section('meta')
    <meta class="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            {{-- Session Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                {{-- LEFT COLUMN: PROFILE CARD --}}
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0D8ABC&color=fff&size=256' }}"
                                    alt="Profile Avatar">
                            </div>

                            <h3 class="profile-username text-center">
                                {{ auth()->user()->name }}
                            </h3>

                            <p class="text-muted text-center mb-0">
                                {{ auth()->user()->email }}
                            </p>
                            <p class="text-center mt-0">
                                <span class="badge badge-secondary">@ {{ auth()->user()->username }}</span>
                            </p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Employee ID</b>
                                    <span class="float-right text-muted">{{ auth()->user()->employee_id ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Assigned Office</b>
                                    <span class="float-right text-muted">
                                        {{ auth()->user()->office->name ?? 'Unassigned' }}
                                        <span
                                            class="badge badge-secondary ml-1">{{ auth()->user()->office->office_type ?? '' }}</span>
                                        @if (auth()->user()->office?->isSchool())
                                            <small class="d-block text-right text-secondary">School ID:
                                                {{ auth()->user()->office->school_id }}</small>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Account Status</b>
                                    <span class="float-right">
                                        <span
                                            class="badge badge-{{ auth()->user()->status === 'active' ? 'success' : (auth()->user()->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(auth()->user()->status) }}
                                        </span>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile Number</b>
                                    <span
                                        class="float-right text-muted">{{ auth()->user()->mobile_number ?? 'Not Provided' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Roles</b>
                                    <span class="float-right">
                                        @forelse (auth()->user()->roles as $role)
                                            <span class="badge badge-primary">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-muted small">No roles assigned</span>
                                        @endforelse
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: FORMS --}}
                <div class="col-md-8">
                    {{-- Update Profile Details --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Update Profile</h3>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                {{-- SECTION: AUTH & DETAILS --}}
                                <h5 class="text-primary border-bottom pb-2 mt-3 mb-3">Profile Information</h5>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label small text-muted mb-1" for="username">Username</label>
                                        <input type="text" name="username" id="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username', auth()->user()->username) }}" required
                                            autocomplete="false">
                                        @error('username')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label small text-muted mb-1" for="email">Email
                                            address</label>
                                        <input type="email" id="email" class="form-control"
                                            value="{{ auth()->user()->email }}" disabled>
                                        <small class="text-muted">Contact the administrator if there are any
                                            changes.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button class="btn btn-primary px-4">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Change Password --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Change Password</h3>
                        </div>

                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1" for="current_password">Current
                                        Password</label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1" for="password">New Password</label>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1" for="password_confirmation">Confirm
                                        Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button class="btn btn-warning text-dark px-4">
                                    <i class="fas fa-key mr-1"></i> Change Password
                                </button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
