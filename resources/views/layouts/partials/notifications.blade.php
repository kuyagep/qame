<!-- Toastr.js & SweetAlert2 Configuration Bridge -->
<link rel="stylesheet" href="{{ asset('v1/plugins/toastr/toastr.min.css') }}">
<script src="{{ asset('v1/plugins/toastr/toastr.min.js') }}"></script>

<script>
    // Configure Toastr global performance options
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    window.SystemAlert = {
        /**
         * Top-right transient notification toast alert via Toastr.js
         * @param {string} icon - 'success' | 'error' | 'warning' | 'info'
         * @param {string} message - Text payload to present
         */
        toast: function(icon, message) {
            // Map common terms to direct toastr API function methods smoothly
            if (icon === 'danger') icon = 'error';

            if (typeof toastr[icon] === 'function') {
                toastr[icon](message);
            } else {
                toastr.info(message);
            }
        },

        /**
         * Keeping SweetAlert2 purely for structural confirmation overlays
         * because Toastr doesn't support interactive confirm dialog buttons natively.
         */
        confirmPurge: function(title, text, callback) {
            Swal.fire({
                title: title || 'Are you absolutely sure?',
                text: text || "This item will be permanently removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e9573f',
                cancelButtonColor: '#4a89dc',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }
    };

    // Auto-trigger intercepts for standard backend session redirects
    @if (session('success'))
        SystemAlert.toast('success', "{{ session('success') }}");
    @endif

    @if (session('error'))
        SystemAlert.toast('error', "{{ session('error') }}");
    @endif

    @if ($errors->any())
        SystemAlert.toast('error', "{{ $errors->first() }}");
    @endif
</script>


<script>
    $(document).ready(function() {
        $('#mark-all-read').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('notifications.clear') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    // Clear the badges and text counters gracefully
                    $('.navbar-badge').fadeOut().remove();
                    $('.dropdown-header').text('0 Unread Notifications');
                    SystemAlert.toast('success', 'Notification alerts queue cleared.');

                    // Reload location briefly after a short window to clean views if needed
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                }
            });
        });
    });
</script>
