document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    const currentAvatar = document.getElementById('current-avatar');
    const removeAvatarBtn = document.getElementById('remove-avatar');
    
    if (avatarInput && avatarPreview) {
        // Show file preview on selection
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, GIF)');
                    this.value = '';
                    return;
                }
                
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should be less than 2MB');
                    this.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.classList.remove('hidden');
                    if (currentAvatar) {
                        currentAvatar.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Remove avatar functionality
    if (removeAvatarBtn) {
        removeAvatarBtn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove your avatar?')) {
                e.preventDefault();
            }
        });
    }
});
