<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Set SweetAlert2 defaults that match your app's design
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Function for confirmation dialogs
    window.confirmDialog = function(options) {
        const defaultOptions = {
            title: '{{ __("Are you sure?") }}',
            text: '{{ __("This action cannot be undone.") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Yes, proceed!") }}',
            cancelButtonText: '{{ __("Cancel") }}'
        };
        
        return Swal.fire({...defaultOptions, ...options});
    }
    
    // Function for delete confirmations
    window.confirmDelete = function(options = {}) {
        const deleteOptions = {
            title: '{{ __("Confirm Deletion") }}',
            text: '{{ __("Are you sure you want to delete? This action cannot be undone.") }}',
            icon: 'error',
            confirmButtonText: '{{ __("Yes, delete!") }}'
        };
        
        return confirmDialog({...deleteOptions, ...options});
    }
    
    // Function for bulk delete confirmations
    window.confirmBulkDelete = function(count, options = {}) {
        const bulkDeleteOptions = {
            title: '{{ __("Confirm Bulk Deletion") }}',
            text: `{{ __("Are you sure you want to delete") }} ${count} {{ __("items?") }}`,
            icon: 'error',
            confirmButtonText: '{{ __("Yes, delete all!") }}'
        };
        
        return confirmDialog({...bulkDeleteOptions, ...options});
    }
    
    // Show success toast message
    window.showSuccess = function(message) {
        Toast.fire({
            icon: 'success',
            title: message
        });
    }
    
    // Show error toast message
    window.showError = function(message) {
        Toast.fire({
            icon: 'error',
            title: message
        });
    }

    // Show session flash messages as toasts
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showSuccess('{{ session('success') }}');
        @endif
        
        @if(session('error'))
            showError('{{ session('error') }}');
        @endif
    });
</script>
