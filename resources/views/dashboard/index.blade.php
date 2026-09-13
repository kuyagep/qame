@extends('layouts.main')

@section('title', 'Dashboard')
@section('content-header', 'Dashboard')

@section('content')
    <div class="container-fluid">
        {{-- Welcome --}}
        <div class="row mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            Welcome back, <b>{{ auth()->user()->name }}!</b>
                        </h4>

                        <p class="text-muted mb-0">
                            <i class="fas fa-regular fa-calendar"></i>
                            <span id="currentSystemTime"></span>
                        </p>

                    </div>
                </div>
            </div>
        </div>

        @role('Super Admin')
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <x-stat-card title="Users" :value="$users" icon="fas fa-users" color="info" />
                </div>

                <div class="col-lg-3 col-md-6">
                    <x-stat-card title="Roles" :value="$roles" icon="fas fa-user-shield" color="purple" />
                </div>

                <div class="col-lg-3 col-md-6">

                    <x-stat-card title="Permissions" :value="$permissions" icon="fas fa-key" color="teal" />

                </div>

                <div class="col-lg-3 col-md-6">

                    <x-stat-card title="Online Users" :value="1" icon="fas fa-solid fa-chart-pie" color="dark" />

                </div>

            </div>

            {{-- Tables --}}

            <div class="row">

                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <strong>

                                Recent Users

                            </strong>

                        </div>

                        <div class="card-body table-responsive">

                            <table class="table table-hover">

                                <thead>

                                    <tr>

                                        <th>Name</th>

                                        <th>Email</th>

                                        <th>Created</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($recentUsers as $user)
                                        <tr>
                                            <td class="align-middle">
                                                <div class="user-block">
                                                    <img id="avatar-preview-{{ $user->id }}"
                                                        class="img-circle img-bordered-sm" style="object-fit: cover;"
                                                        src="{{ route('users.avatar', $user->id) }}"
                                                        alt="{{ $user->name }} Avatar">
                                                    <span class="username">
                                                        <a href="#">{{ $user->name }}</a>
                                                    </span>
                                                    <span class="description">{{ $user->email }}</span>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                @if ($user->email_verified_at)
                                                    <span class="badge badge-success bg-success">Verified</span>
                                                @else
                                                    <span class="badge badge-warning bg-warning text-dark">Unverified</span>
                                                @endif
                                            </td>

                                            <td class="align-middle">{{ $user->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Links</h3>
                        </div>
                        <div class="card-body">

                            <a class="btn btn-app">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a class="btn btn-app">
                                <i class="fas fa-play"></i> Play
                            </a>
                            <a class="btn btn-app">
                                <i class="fas fa-pause"></i> Pause
                            </a>
                            <a class="btn btn-app">
                                <i class="fas fa-save"></i> Save
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-warning">3</span>
                                <i class="fas fa-bullhorn"></i> Notifications
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-success">300</span>
                                <i class="fas fa-barcode"></i> Products
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-purple">891</span>
                                <i class="fas fa-users"></i> Users
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-teal">67</span>
                                <i class="fas fa-inbox"></i> Orders
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-info">12</span>
                                <i class="fas fa-envelope"></i> Inbox
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-danger">531</span>
                                <i class="fas fa-heart"></i> Likes
                            </a>

                            <p>Application Buttons with Custom Colors</p>
                            <a class="btn btn-app bg-secondary">
                                <span class="badge bg-success">300</span>
                                <i class="fas fa-barcode"></i> Products
                            </a>
                            <a class="btn btn-app bg-success">
                                <span class="badge bg-purple">891</span>
                                <i class="fas fa-users"></i> Users
                            </a>
                            <a class="btn btn-app bg-danger">
                                <span class="badge bg-teal">67</span>
                                <i class="fas fa-inbox"></i> Orders
                            </a>
                            <a class="btn btn-app bg-warning">
                                <span class="badge bg-info">12</span>
                                <i class="fas fa-envelope"></i> Inbox
                            </a>
                            <a class="btn btn-app bg-info">
                                <span class="badge bg-danger">531</span>
                                <i class="fas fa-heart"></i> Likes
                            </a>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>

            </div>
        @endrole

        <div class="row mb-3">
            <div class="col">
                @if (isset($announcements) && $announcements->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-bold font-weight-bold mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-bullhorn text-primary me-2 mr-2"></i> Announcement Feed
                            </h5>
                        </div>

                        <div class="card-body p-0 max-vh-50 overflow-auto">
                            <div class="list-group list-group-flush">
                                @foreach ($announcements as $announcement)
                                    @php
                                        // Type configuration mapping
                                        $config = match ($announcement->type) {
                                            'warning' => [
                                                'icon' => 'fa-exclamation-triangle',
                                                'bg' => 'bg-warning',
                                                'text' => 'text-warning',
                                                'badge' => 'Warning',
                                            ],
                                            'danger' => [
                                                'icon' => 'fa-bell',
                                                'bg' => 'bg-danger',
                                                'text' => 'text-danger',
                                                'badge' => 'Urgent',
                                            ],
                                            'success' => [
                                                'icon' => 'fa-check-circle',
                                                'bg' => 'bg-success',
                                                'text' => 'text-success',
                                                'badge' => 'Notice',
                                            ],
                                            default => [
                                                'icon' => 'fa-info-circle',
                                                'bg' => 'bg-info',
                                                'text' => 'text-info',
                                                'badge' => 'Info',
                                            ],
                                        };
                                    @endphp

                                    <div class="list-group-item p-3 border-bottom transition-hover">
                                        <div class="d-flex align-items-start">

                                            {{-- Type Icon Avatar / Indicator --}}
                                            <div class="rounded-circle {{ $config['bg'] }} bg-opacity-10 {{ $config['text'] }} d-flex align-items-center justify-content-center me-3 mr-3 flex-shrink-0"
                                                style="width: 42px; height: 42px;">
                                                <i class="fas {{ $config['icon'] }} fs-5"></i>
                                            </div>

                                            {{-- News Feed Item Content --}}
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <h6 class="fw-bold font-weight-bold mb-0 text-dark text-truncate pe-2">
                                                        {{ $announcement->title }}
                                                    </h6>
                                                </div>

                                                {{-- Rich Text Body --}}
                                                <div class="announcement-body text-secondary small mb-2">
                                                    {!! $announcement->content !!}
                                                </div>

                                                {{-- Timestamp Footer --}}
                                                <div class="d-flex align-items-center text-muted extra-small">
                                                    <i class="far fa-clock me-1 mr-1"></i>
                                                    <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                                    <span class="mx-1">•</span>
                                                    <span>{{ $announcement->created_at->format('M d, Y h:i A') }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div><!--/. container-fluid -->


@endsection
