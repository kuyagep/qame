@extends('layouts.main')

@section('title', 'System Settings')
@section('content-header', 'Global System Configuration')

@section('header-action')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                {{-- Form configured for file uploads --}}
                <form id="settingsForm" enctype="multipart/form-data">
                    <div class="card card-outline card-navy shadow-sm">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-bold" id="general-tab" data-toggle="pill"
                                        href="#tab-general" role="tab">
                                        <i class="fas fa-sliders-h mr-1"></i> General Controls
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" id="branding-tab" data-toggle="pill"
                                        href="#tab-branding" role="tab">
                                        <i class="fas fa-image mr-1"></i> System Branding
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" id="mail-tab" data-toggle="pill" href="#tab-mail"
                                        role="tab">
                                        <i class="fas fa-server mr-1"></i> SMTP Email Engine
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="alert alert-danger d-none" id="form-errors"></div>

                            <div class="tab-content" id="settingsTabContent">
                                <!-- GENERAL CONTROL SCHEMAS -->
                                <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Application Branding Title</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="app_name"
                                                value="{{ $settings['app_name'] ?? 'System Framework Core' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Global Helpdesk Contact Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="contact_email"
                                                value="{{ $settings['contact_email'] ?? 'helpdesk@domain.local' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">System Lockout Maintenance Mode</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="maintenance_mode">
                                                <option value="0"
                                                    {{ ($settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : '' }}>
                                                    Disabled (System Active & Live)</option>
                                                <option value="1"
                                                    {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : '' }}>
                                                    Enabled (Admin Lockout Active)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Public User Self-Registration</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="allow_registration">
                                                <option value="1"
                                                    {{ ($settings['allow_registration'] ?? '1') == '1' ? 'selected' : '' }}>
                                                    Open (Public Registrations Allowed)</option>
                                                <option value="0"
                                                    {{ ($settings['allow_registration'] ?? '1') == '0' ? 'selected' : '' }}>
                                                    Closed (Invite-Only Entry Profiles)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- BRANDING & LOGO TAB -->
                                <div class="tab-pane fade" id="tab-branding" role="tabpanel">
                                    <!-- Application Logo -->
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label">Application Logo</label>
                                        <div class="col-sm-6">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input image-input" id="app_logo"
                                                    name="app_logo" accept="image/png,image/jpeg,image/jpg,image/svg+xml">
                                                <label class="custom-file-label" for="app_logo">Choose logo file...</label>
                                            </div>
                                            <small class="form-text text-muted">Recommended format: PNG or SVG (Max:
                                                2MB)</small>
                                        </div>
                                        <div class="col-sm-3 text-center">
                                            {{-- Placehold.co fallback for Logo --}}
                                            <img id="app_logo_preview"
                                                src="{{ !empty($settings['app_logo']) ? route('image.show', 'app_logo') : 'https://placehold.co/200x60/001f3f/ffffff?text=System+Logo' }}"
                                                alt="Logo Preview" class="img-thumbnail" style="max-height: 125px;">
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Application Favicon -->
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label">System Favicon</label>
                                        <div class="col-sm-6">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input image-input" id="app_favicon"
                                                    name="app_favicon" accept="image/x-icon,image/png,image/ico">
                                                <label class="custom-file-label" for="app_favicon">Choose favicon
                                                    file...</label>
                                            </div>
                                            <small class="form-text text-muted">Recommended format: ICO or 32x32 PNG (Max:
                                                1MB)</small>
                                        </div>
                                        <div class="col-sm-3 text-center">
                                            {{-- Placehold.co fallback for Favicon --}}
                                            <img id="app_favicon_preview"
                                                src="{{ !empty($settings['app_favicon']) ? route('image.show', 'app_favicon') : 'https://placehold.co/64x64/001f3f/ffffff?text=ICO' }}"
                                                alt="Favicon Preview" class="img-thumbnail" style="max-height: 50px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- MAIL SMTP ENGINE SCHEMAS -->
                                <div class="tab-pane fade" id="tab-mail" role="tabpanel">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Transport Mail Driver</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="mail_driver"
                                                value="{{ $settings['mail_driver'] ?? 'smtp' }}" placeholder="smtp">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Relay Mail Server Host</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="mail_host"
                                                value="{{ $settings['mail_host'] ?? 'sandbox.smtp.mailtrap.io' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">SMTP Authentication Port</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" name="mail_port"
                                                value="{{ $settings['mail_port'] ?? '2525' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">SMTP Connection Username</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="mail_username"
                                                value="{{ $settings['mail_username'] ?? '' }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">SMTP Connection Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="mail_password"
                                                value="{{ $settings['mail_password'] ?? '' }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Global Outbound Sender Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="mail_from_address"
                                                value="{{ $settings['mail_from_address'] ?? 'noreply@yourdomain.com' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-navy px-4 font-weight-bold" id="btn-save-settings">
                                <i class="fas fa-save mr-1"></i> Sync Configurations
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Update file input custom label and show image preview instantly
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);

                let inputId = $(this).attr('id');
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#' + inputId + '_preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Form Submit via AJAX
            $('#settingsForm').submit(function(e) {
                e.preventDefault();
                $('#btn-save-settings').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Processing Changes...');
                $('#form-errors').addClass('d-none').html('');

                let formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('settings.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(res) {
                        $('#btn-save-settings').prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Sync Configurations');

                        SystemAlert.toast('success', res.message);
                    },
                    error: function(xhr) {
                        $('#btn-save-settings').prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Sync Configurations');

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorList = '<ul class="mb-0">';
                            $.each(errors, function(key, value) {
                                errorList += `<li>${value[0]}</li>`;
                            });
                            errorList += '</ul>';
                            $('#form-errors').removeClass('d-none').html(errorList);
                            SystemAlert.toast('error', 'Form validation verification crashed.');
                        } else {
                            SystemAlert.toast('error',
                                'Internal runtime server pipeline fault.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
