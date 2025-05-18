//pour l'upload d'images
document.addEventListener('DOMContentLoaded', function() {
    const profileImageInput = document.getElementById('profile_image');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const profileAvatar = document.querySelector('.profile-avatar');
                    if (profileAvatar) {
                        profileAvatar.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});