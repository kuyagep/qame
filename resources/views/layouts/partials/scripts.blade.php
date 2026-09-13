<script src="{{ asset('v1/') }}/plugins/jquery/jquery.min.js"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
<script src="{{ asset('v1/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('v1/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('v1/dist/js/adminlte.js') }}"></script>
<script src="{{ asset('v1/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('v1/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('v1/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('v1/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<script src="{{ asset('v1/plugins/chart.js/Chart.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    document.getElementById('btnLogout').addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Logout?',
            text: 'Are you sure you want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Please wait.',
                    icon: 'success',
                    timer: 800,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                }).then(() => {
                    document.getElementById('logout-form').submit();
                });
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('sidebarToggle');

        function updateClock() {
            const el = document.getElementById('currentSystemTime');
            if (el) el.textContent = new Date().toLocaleDateString('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        updateClock();
        setInterval(updateClock, 1000);
    });
</script>
@include('layouts.partials.notifications')
